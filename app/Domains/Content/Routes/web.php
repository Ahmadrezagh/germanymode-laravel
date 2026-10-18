<?php

use App\Domains\Content\Http\Controllers\Admin\ContentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/blog', [ContentController::class, 'blog'])->name('blog');
        Route::get('/gallery', [ContentController::class, 'gallery'])->name('gallery');
        Route::get('/about', [ContentController::class, 'about'])->name('about');
        Route::post('/about', [ContentController::class, 'saveAbout'])->name('about.save');
        Route::get('/contact', [ContentController::class, 'contact'])->name('contact');
        Route::post('/contact/{message}/seen', [ContentController::class, 'markContactSeen'])->name('contact.seen');
        Route::delete('/contact/{message}', [ContentController::class, 'destroyContact'])->name('contact.destroy');
        Route::get('/quick-links', [ContentController::class, 'quickLinks'])->name('quick-links');
        Route::post('/quick-links', [ContentController::class, 'storeQuickLink'])->name('quick-links.store');
        Route::delete('/quick-links/{link}', [ContentController::class, 'destroyQuickLink'])->name('quick-links.destroy');
        Route::post('/posts', [ContentController::class, 'storePost'])->name('posts.store');
        Route::post('/posts/{post}/toggle', [ContentController::class, 'togglePost'])->name('posts.toggle');
        Route::delete('/posts/{post}', [ContentController::class, 'destroyPost'])->name('posts.destroy');
    });
});
