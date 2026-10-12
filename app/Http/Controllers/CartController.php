<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = collect(Cart::items())->values();
        $count = Cart::count();
        $subtotal = Cart::subtotal();

        return view('shop.cart', compact('items', 'count', 'subtotal'));
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        Cart::add($product, (int) ($data['qty'] ?? 1));

        return redirect()
            ->back()
            ->with('ok', 'کالا به سبد خرید اضافه شد.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        Cart::update((int) $data['product_id'], (int) $data['qty']);

        return redirect()->route('shop.cart')->with('ok', 'سبد خرید به‌روزرسانی شد.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        Cart::remove((int) $data['product_id']);

        return redirect()->route('shop.cart')->with('ok', 'کالا از سبد حذف شد.');
    }
}
