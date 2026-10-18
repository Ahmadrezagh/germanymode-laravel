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

        $products = Product::query()
            ->with('media')
            ->when($q !== '', function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            })
            ->latest('id')
            ->paginate(24)
            ->appends(['q' => $q]);

        $categories = Category::query()->whereNull('parent_id')->orderBy('name')->get();
        $stories = collect();

        return view('shop.search', compact('products', 'categories', 'q', 'stories'));
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

        $products = Product::query()
            ->with('media')
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            })
            ->latest('id')
            ->limit(5)
            ->get();

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
}
