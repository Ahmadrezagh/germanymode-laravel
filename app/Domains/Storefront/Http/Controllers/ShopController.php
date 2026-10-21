<?php

namespace App\Domains\Storefront\Http\Controllers;

use App\Shared\Http\Controller;

use App\Domains\Marketing\Models\Banner;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Marketing\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Throwable;


class ShopController extends Controller
{
    public function home(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->withCount('products')->orderBy('name')])
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $stories = Story::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $featured = Product::query()
            ->with('media')
            ->where(function ($q) {
                $q->where('is_featured', true)->orWhere('is_special_discount', true);
            })
            ->whereEffectivePriceGreaterThan(0)
            ->latest('id')
            ->take(16)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Product::query()->with('media')->whereEffectivePriceGreaterThan(0)->latest('id')->take(16)->get();
        }

        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $specials = Product::query()
            ->with('media')
            ->where('is_special_discount', true)
            ->whereEffectivePriceGreaterThan(0)
            ->latest('id')
            ->take(16)
            ->get();

        $newest = Product::query()
            ->with('media')
            ->whereEffectivePriceGreaterThan(0)
            ->latest('id')
            ->take(24)
            ->get();

        $categoryRails = $categories
            ->map(function (Category $cat) {
                $childIds = $cat->children->pluck('id')->push($cat->id);
                $railProducts = Product::query()
                    ->with('media')
                    ->whereIn('category_id', $childIds)
                    ->whereEffectivePriceGreaterThan(0)
                    ->latest('id')
                    ->take(14)
                    ->get();
                $cat->setRelation('railProducts', $railProducts);
                $cat->rail_total = $railProducts->count();

                return $cat;
            })
            ->filter(fn (Category $cat) => $cat->railProducts->isNotEmpty())
            ->sortByDesc('rail_total')
            ->take(10)
            ->values();

        $products = Product::query()
            ->with(['category', 'media'])
            ->latest('id')
            ->paginate(24);

        return view('shop.home', compact(
            'categories',
            'stories',
            'banners',
            'featured',
            'specials',
            'newest',
            'categoryRails',
            'products'
        ));
    }

    public function category(Category $category): View
    {
        $products = Product::query()
            ->with('media')
            ->whereIn('category_id', $category->descendantIds())
            ->latest('id')
            ->paginate(24);

        $categories = Category::megaMenuTree();
        $megaCategories = $categories;
        $stories = collect();

        return view('shop.category', compact('category', 'products', 'categories', 'megaCategories', 'stories'));
    }

    public function product(Product $product): View
    {
        return $this->renderProduct($product);
    }

    public function productBySlug(string $slug, ?int $product = null): View
    {
        if ($product) {
            return $this->renderProduct(Product::query()->findOrFail($product));
        }

        if (ctype_digit($slug)) {
            return $this->renderProduct(Product::query()->findOrFail((int) $slug));
        }

        $needle = str_replace('-', '%', $slug);
        $match = Product::query()
            ->where('title', 'like', "%{$needle}%")
            ->orderByDesc('id')
            ->first();

        abort_unless($match, 404);

        return $this->renderProduct($match);
    }

    private function renderProduct(Product $product): View
    {
        $product->load(['category', 'models', 'brand', 'media']);
        $related = Product::query()
            ->with('media')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereEffectivePriceGreaterThan(0)
            ->latest('id')
            ->take(12)
            ->get();

        $categories = Category::query()->whereNull('parent_id')->orderBy('name')->get();
        $stories = collect();

        return view('shop.product', compact('product', 'related', 'categories', 'stories'));
    }

    
public function search(Request $request): View
{
    $q = trim((string) $request->get('q', ''));
    $perPage = 24;
    $page = LengthAwarePaginator::resolveCurrentPage();

    if ($q === '') {
        $products = Product::query()
            ->with('media')
            ->latest('id')
            ->paginate($perPage)
            ->appends(['q' => $q]);
    } else {
        try {
            $offset = ($page - 1) * $perPage;

            $result = $this->searchProductsInElasticsearch(
                $q,
                $offset,
                $perPage
            );

            $ids = $result['ids'];
            $total = $result['total'];

            $items = collect();

            if ($ids !== []) {
                $productsById = Product::query()
                    ->with('media')
                    ->whereIn('id', $ids)
                    ->get()
                    ->keyBy(fn (Product $product) => (string) $product->id);

                // Preserve Elasticsearch relevance order.
                $items = collect($ids)
                    ->map(fn ($id) => $productsById->get((string) $id))
                    ->filter()
                    ->values();
            }

            $products = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );

            $products->appends(['q' => $q]);
        } catch (Throwable $e) {
            Log::warning('Elasticsearch product search failed.', [
                'query' => $q,
                'exception' => $e->getMessage(),
            ]);

            // Keep the storefront working if Elasticsearch is unavailable.
            $products = Product::query()
                ->with('media')
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere(
                            'short_description',
                            'like',
                            "%{$q}%"
                        );
                })
                ->latest('id')
                ->paginate($perPage)
                ->appends(['q' => $q]);
        }
    }

    $categories = Category::query()
        ->whereNull('parent_id')
        ->orderBy('name')
        ->get();

    $stories = collect();

    return view(
        'shop.search',
        compact('products', 'categories', 'q', 'stories')
    );
}

public function suggest(Request $request): JsonResponse
{
    $q = trim((string) $request->get('q', ''));

    if (mb_strlen($q) < 2) {
        return response()->json([
            'q' => $q,
            'items' => [],
            'more_url' => route('shop.search', ['q' => $q]),
        ]);
    }

    try {
        $result = $this->searchProductsInElasticsearch($q, 0, 5);
        $ids = $result['ids'];

        $products = collect();

        if ($ids !== []) {
            $productsById = Product::query()
                ->with('media')
                ->whereIn('id', $ids)
                ->get()
                ->keyBy(fn (Product $product) => (string) $product->id);

            // Preserve Elasticsearch relevance order.
            $products = collect($ids)
                ->map(fn ($id) => $productsById->get((string) $id))
                ->filter()
                ->values();
        }
    } catch (Throwable $e) {
        Log::warning('Elasticsearch product suggestions failed.', [
            'query' => $q,
            'exception' => $e->getMessage(),
        ]);

        // Database fallback if Elasticsearch is unavailable.
        $products = Product::query()
            ->with('media')
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$q}%"
                    );
            })
            ->latest('id')
            ->limit(5)
            ->get();
    }

    return response()->json([
        'q' => $q,
        'items' => $products->map(fn (Product $product) => [
            'id' => $product->id,
            'title' => $product->title,
            'url' => $product->url(),
            'image' => $product->image,
            'price' => $product->formattedPrice(),
        ])->values(),
        'more_url' => route('shop.search', ['q' => $q]),
    ]);
}

/**
 * Search products through Elasticsearch.
 *
 * Returns product IDs in relevance order and the total hit count.
 */
private function searchProductsInElasticsearch(
    string $q,
    int $offset,
    int $size
): array {
    $config = config('services.elasticsearch');

    $client = ClientBuilder::create()
        ->setHosts([$config['host']])
        ->setBasicAuthentication(
            $config['username'],
            $config['password']
        )
        ->setCABundle($config['ca'])
        ->build();

    $response = $client->search([
        'index' => 'products_v1',
        'body' => [
            'from' => $offset,
            'size' => $size,
            'track_total_hits' => true,
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'term' => [
                                'title.keyword' => [
                                    'value' => $q,
                                    'boost' => 15,
                                ],
                            ],
                        ],
                        [
                            'match_phrase' => [
                                'title' => [
                                    'query' => $q,
                                    'boost' => 5,
                                ],
                            ],
                        ],
                        [
                            'multi_match' => [
                                'query' => $q,
                                'fields' => [
                                    'title^4',
                                    'short_description^2',
                                    'specifications',
                                ],
                                'type' => 'best_fields',
                            ],
                        ],
                    ],
                    'minimum_should_match' => 1,
                ],
            ],
            'sort' => [
                ['_score' => ['order' => 'desc']],
                ['id' => ['order' => 'desc']],
            ],
        ],
    ])->asArray();

    $hits = $response['hits']['hits'] ?? [];
    $totalData = $response['hits']['total'] ?? 0;

    $total = is_array($totalData)
        ? (int) ($totalData['value'] ?? 0)
        : (int) $totalData;

    return [
        'ids' => array_values(array_map(
            fn (array $hit) => (string) $hit['_id'],
            $hits
        )),
        'total' => $total,
    ];
}

}
