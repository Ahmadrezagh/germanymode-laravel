<?php

namespace App\Domains\Catalog\Http\Controllers\Admin;

use App\Shared\Http\Controller;
use App\Domains\Catalog\Models\Brand;
use App\Domains\Catalog\Models\Category;
use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShopController extends Controller
{
    public function excelAdd(): View
    {
        return view('admin.shop.excel-add', $this->formLookups());
    }

    public function excelEdit(): View
    {
        return view('admin.shop.excel-edit');
    }

    public function quickEdit(Request $request): View
    {
        $perPage = (int) $request->get('per_page', 50);
        $perPage = in_array($perPage, [10, 50, 100, 200, 300, 500], true) ? $perPage : 50;

        $products = Product::query()
            ->with(['category', 'warehouse', 'brand', 'media'])
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->get('scope') === 'placed', fn ($q) => $q->whereNotNull('category_id'))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->q.'%'))
            ->latest('id')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.shop.quick-edit', array_merge($this->formLookups(), [
            'products' => $products,
            'q' => (string) $request->get('q', ''),
            'perPage' => $perPage,
        ]));
    }

    public function quickEditSave(Request $request): RedirectResponse
    {
        $rows = $request->input('products', []);
        foreach ($rows as $id => $data) {
            $product = Product::query()->find($id);
            if (! $product) {
                continue;
            }
            $product->update([
                'title' => $data['title'] ?? $product->title,
                'discounted_price' => (int) ($data['discounted_price'] ?? $product->discounted_price),
                'regular_price' => $data['regular_price'] !== '' && $data['regular_price'] !== null
                    ? (int) $data['regular_price']
                    : $product->regular_price,
                'status' => $data['status'] ?? $product->status,
            ]);
        }

        return back()->with('success', 'تغییرات سریع کالاها ذخیره شد.');
    }

    public function specialDiscounts(): View
    {
        $products = Product::query()
            ->with(['category', 'media'])
            ->where(function ($q) {
                $q->where('is_special_discount', true)
                    ->orWhere(function ($q2) {
                        $q2->whereNotNull('regular_price')
                            ->where('discounted_price', '>', 0)
                            ->whereColumn('discounted_price', '<', 'regular_price');
                    });
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(30);

        return view('admin.shop.special-discounts', compact('products'));
    }

    public function featured(): View
    {
        $products = Product::query()
            ->with(['category', 'media'])
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(30);

        if ($products->total() === 0) {
            $products = Product::query()
                ->with(['category', 'media'])
                ->whereEffectivePriceGreaterThan(0)
                ->latest('id')
                ->paginate(30);
        }

        return view('admin.shop.featured', compact('products'));
    }

    public function prepare(): View
    {
        $warehouses = Warehouse::query()->orderByDesc('id')->paginate(10, ['*'], 'w_page');
        $brands = Brand::query()->orderByDesc('id')->paginate(10, ['*'], 'b_page');

        return view('admin.shop.prepare', compact('warehouses', 'brands'));
    }

    public function storeWarehouse(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);
        Warehouse::query()->create($data);

        return back()->with('success', 'انبار ذخیره شد.');
    }

    public function destroyWarehouse(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return back()->with('success', 'انبار حذف شد.');
    }

    public function storeBrand(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255']]);
        Brand::query()->create($data);

        return back()->with('success', 'برند ذخیره شد.');
    }

    public function destroyBrand(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return back()->with('success', 'برند حذف شد.');
    }

    public function categories(Request $request): View
    {
        $categories = Category::query()
            ->with('parent')
            ->withCount('products')
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
            })
            ->orderBy('id')
            ->paginate(40)
            ->appends($request->query());

        return view('admin.shop.categories', [
            'categories' => $categories,
            'q' => (string) $request->get('q', ''),
        ]);
    }

    public function android(): View
    {
        return view('admin.shop.android');
    }

    public function sampleExcel(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['title', 'discounted_price', 'regular_price', 'status', 'brand', 'warehouse', 'short_description']);
            fputcsv($out, ['نمونه کالا', '1000000', '1200000', 'موجود', 'فیلیپس', 'برقی آشپزخانه', 'توضیح کوتاه']);
            fclose($out);
        }, 'sample-products.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        $products = Product::query()->with(['brand', 'warehouse'])->latest('id')->limit(500)->get();

        return response()->streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['id', 'title', 'discounted_price', 'regular_price', 'status', 'brand', 'warehouse']);
            foreach ($products as $p) {
                fputcsv($out, [
                    $p->id,
                    $p->title,
                    $p->discounted_price,
                    $p->regular_price,
                    $p->status,
                    $p->brand?->name,
                    $p->warehouse?->name,
                ]);
            }
            fclose($out);
        }, 'products-export.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @return array<string, mixed> */
    private function formLookups(): array
    {
        return [
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
        ];
    }
}
