<?php

namespace App\Domains\Catalog\Http\Controllers\Admin;

use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Warehouse;
use App\Domains\Media\Actions\SyncModelMedia;
use App\Shared\Http\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $perPage = (int) $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 50, 100, 200, 300, 500], true) ? $perPage : 20;

        $products = Product::query()
            ->with(['category', 'warehouse', 'brand', 'media'])
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->when($request->filled('warehouse_id'), fn ($query) => $query->where('warehouse_id', $request->warehouse_id))
            ->latest('id')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.products.index', [
            'products' => $products,
            'q' => $q,
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', array_merge($this->formData(), [
            'product' => new Product(['status' => 'موجود']),
            'mode' => 'create',
        ]));
    }

    public function store(Request $request, SyncModelMedia $syncMedia): RedirectResponse
    {
        [$attributes, $galleryPaths] = $this->validated($request);
        $product = Product::query()->create($attributes);
        $syncMedia->handle($product, $galleryPaths);

        return redirect()->route('admin.products.index')->with('success', 'محصول ذخیره شد.');
    }

    public function edit(Product $product): View
    {
        $product->load('media');

        return view('admin.products.form', array_merge($this->formData(), [
            'product' => $product,
            'mode' => 'edit',
        ]));
    }

    public function update(Request $request, Product $product, SyncModelMedia $syncMedia): RedirectResponse
    {
        [$attributes, $galleryPaths] = $this->validated($request);
        $product->update($attributes);
        $syncMedia->handle($product, $galleryPaths);

        return redirect()->route('admin.products.index')->with('success', 'محصول به‌روزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->media()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'محصول حذف شد.');
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        return [
            'categories' => Category::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
        ];
    }

    /**
     * @return array{0: array<string, mixed>, 1: list<string>}
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'short_description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'discounted_price' => ['nullable', 'integer', 'min:0'],
            'regular_price' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'status' => ['nullable', 'string', 'max:50'],
            'gallery_urls' => ['nullable', 'string'],
            'is_featured' => ['nullable'],
            'is_special_discount' => ['nullable'],
            'meta' => ['nullable', 'array'],
        ]);

        $galleryPaths = collect(preg_split('/\r\n|\r|\n/', (string) ($data['gallery_urls'] ?? '')))
            ->map(fn ($u) => trim($u))
            ->filter()
            ->values()
            ->all();

        unset($data['gallery_urls']);

        $data['discounted_price'] = (int) ($data['discounted_price'] ?? 0);
        $data['regular_price'] = isset($data['regular_price']) ? (int) $data['regular_price'] : null;
        $data['status'] = $data['status'] ?? 'موجود';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_special_discount'] = $request->boolean('is_special_discount');
        $data['meta'] = $data['meta'] ?? [];

        return [$data, $galleryPaths];
    }
}
