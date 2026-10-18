<?php

use App\Domains\Catalog\Http\Controllers\Admin\CategoryController;
use App\Domains\Catalog\Http\Controllers\Admin\ProductController;
use App\Domains\Catalog\Http\Controllers\Admin\ShopController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/excel-add', [ShopController::class, 'excelAdd'])->name('excel-add');
        Route::get('/excel-edit', [ShopController::class, 'excelEdit'])->name('excel-edit');
        Route::get('/quick-edit', [ShopController::class, 'quickEdit'])->name('quick-edit');
        Route::post('/quick-edit', [ShopController::class, 'quickEditSave'])->name('quick-edit.save');
        Route::get('/special-discounts', [ShopController::class, 'specialDiscounts'])->name('special-discounts');
        Route::get('/featured', [ShopController::class, 'featured'])->name('featured');
        Route::get('/prepare', [ShopController::class, 'prepare'])->name('prepare');
        Route::post('/warehouses', [ShopController::class, 'storeWarehouse'])->name('warehouses.store');
        Route::delete('/warehouses/{warehouse}', [ShopController::class, 'destroyWarehouse'])->name('warehouses.destroy');
        Route::post('/brands', [ShopController::class, 'storeBrand'])->name('brands.store');
        Route::delete('/brands/{brand}', [ShopController::class, 'destroyBrand'])->name('brands.destroy');
        Route::get('/categories', [ShopController::class, 'categories'])->name('categories');
        Route::get('/android', [ShopController::class, 'android'])->name('android');
        Route::get('/sample-excel', [ShopController::class, 'sampleExcel'])->name('sample-excel');
        Route::get('/export-excel', [ShopController::class, 'exportExcel'])->name('export-excel');
    });
});
