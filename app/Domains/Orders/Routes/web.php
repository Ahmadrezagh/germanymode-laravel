<?php

use App\Domains\Orders\Http\Controllers\Admin\OrderController;
use App\Domains\Orders\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/cart', [CartController::class, 'index'])->name('shop.cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('shop.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('shop.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('shop.cart.remove');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/orders/new', [OrderController::class, 'newest'])->name('orders.new');
    Route::get('/orders/done', [OrderController::class, 'done'])->name('orders.done');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
