<?php

use App\Domains\Storefront\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'home'])->name('shop.home');
Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/search/suggest', [ShopController::class, 'suggest'])->name('shop.search.suggest');
Route::get('/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{product}', [ShopController::class, 'product'])->name('shop.product');
Route::get('/Product/{slug}/{product?}', [ShopController::class, 'productBySlug'])->name('shop.product.slug');
