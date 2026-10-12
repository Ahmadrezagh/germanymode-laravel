<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
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
            ->with(['category', 'warehouse', 'brand'])
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

    public function store(Request $request): RedirectResponse
    {
        Product::query()->create($this->validated($request));

        return redirect()->route('admin.products.index')->with('success', 'محصول ذخیره شد.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', array_merge($this->formData(), [
            'product' => $product,
            'mode' => 'edit',
        ]));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request));

        return redirect()->route('admin.products.index')->with('success', 'محصول به‌روزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
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

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:500'],
            'short_description' => ['nullable', 'string'],
            'specifications' => ['nullable', 'string'],
            'price' => ['nullable', 'integer', 'min:0'],
            'price_without_discount' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'status' => ['nullable', 'string', 'max:50'],
            'gallery_urls' => ['nullable', 'string'],
            'is_featured' => ['nullable'],
            'is_special_discount' => ['nullable'],
            'meta' => ['nullable', 'array'],
        ]);

        $urls = collect(preg_split('/\r\n|\r|\n/', (string) ($data['gallery_urls'] ?? '')))
            ->map(fn ($u) => trim($u))
            ->filter()
            ->values()
            ->all();

        $data['gallery_urls'] = $urls;
        $data['price'] = (int) ($data['price'] ?? 0);
        $data['status'] = $data['status'] ?? 'موجود';
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_special_discount'] = $request->boolean('is_special_discount');
        $data['meta'] = $data['meta'] ?? [];

        return $data;
    }
}
