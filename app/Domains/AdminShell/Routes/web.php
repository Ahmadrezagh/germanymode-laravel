<?php

use App\Domains\AdminShell\Http\Controllers\DashboardController;
use App\Domains\AdminShell\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/p/{page}', [PageController::class, 'show'])->name('pages.show');
});
