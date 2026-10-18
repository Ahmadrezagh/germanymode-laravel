<?php

use App\Domains\Identity\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Domains\Identity\Http\Controllers\Admin\UserController;
use App\Domains\Identity\Http\Controllers\ShopAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [ShopAuthController::class, 'showLogin'])->name('shop.login');
Route::post('/login', [ShopAuthController::class, 'login'])->name('shop.login.submit');
Route::get('/register', [ShopAuthController::class, 'showRegister'])->name('shop.register');
Route::post('/register', [ShopAuthController::class, 'register'])->name('shop.register.submit');
Route::post('/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
