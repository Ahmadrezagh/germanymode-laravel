<?php

use App\Domains\SiteSettings\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');
    Route::get('/settings/stories', [SettingController::class, 'stories'])->name('settings.stories');
    Route::post('/settings/stories', [SettingController::class, 'storeStory'])->name('settings.stories.store');
    Route::delete('/settings/stories/{story}', [SettingController::class, 'destroyStory'])->name('settings.stories.destroy');
    Route::get('/settings/banners', [SettingController::class, 'banners'])->name('settings.banners');
    Route::post('/settings/banners', [SettingController::class, 'storeBanner'])->name('settings.banners.store');
    Route::delete('/settings/banners/{banner}', [SettingController::class, 'destroyBanner'])->name('settings.banners.destroy');
    Route::get('/settings/toolbar', [SettingController::class, 'toolbar'])->name('settings.toolbar');
    Route::post('/settings/toolbar', [SettingController::class, 'saveToolbar'])->name('settings.toolbar.save');
    Route::get('/settings/mobile-footer', [SettingController::class, 'mobileFooter'])->name('settings.mobile-footer');
    Route::post('/settings/mobile-footer', [SettingController::class, 'storeMobileFooter'])->name('settings.mobile-footer.store');
    Route::delete('/settings/mobile-footer/{item}', [SettingController::class, 'destroyMobileFooter'])->name('settings.mobile-footer.destroy');
    Route::get('/settings/cart-plugin', [SettingController::class, 'cartPlugin'])->name('settings.cart');
    Route::post('/settings/cart-plugin', [SettingController::class, 'saveCartPlugin'])->name('settings.cart.save');
    Route::get('/settings/domain-park', [SettingController::class, 'domainPark'])->name('settings.domain');
    Route::post('/settings/domain-park', [SettingController::class, 'saveDomainPark'])->name('settings.domain.save');
    Route::get('/settings/featured-news', [SettingController::class, 'featuredNews'])->name('settings.featured-news');
    Route::post('/settings/featured-news/{post}', [SettingController::class, 'toggleFeaturedNews'])->name('settings.featured-news.toggle');
    Route::get('/settings/short-links', [SettingController::class, 'shortLinks'])->name('settings.short-links');
    Route::post('/settings/short-links', [SettingController::class, 'storeShortLink'])->name('settings.short-links.store');
    Route::delete('/settings/short-links/{link}', [SettingController::class, 'destroyShortLink'])->name('settings.short-links.destroy');
    Route::get('/settings/url-friendly', [SettingController::class, 'urlFriendly'])->name('settings.url-friendly');
    Route::post('/settings/url-friendly', [SettingController::class, 'storeUrlFriendly'])->name('settings.url-friendly.store');
    Route::delete('/settings/url-friendly/{item}', [SettingController::class, 'destroyUrlFriendly'])->name('settings.url-friendly.destroy');
    Route::get('/settings/files', [SettingController::class, 'files'])->name('settings.files');
    Route::get('/settings/templates', [SettingController::class, 'templates'])->name('settings.templates');
    Route::post('/settings/templates', [SettingController::class, 'saveTemplate'])->name('settings.templates.save');
    Route::get('/settings/plugins', [SettingController::class, 'plugins'])->name('settings.plugins');
    Route::post('/settings/plugins', [SettingController::class, 'savePlugins'])->name('settings.plugins.save');
    Route::get('/settings/photoshop', [SettingController::class, 'photoshop'])->name('settings.photoshop');
    Route::get('/settings/form-builder', [SettingController::class, 'formBuilder'])->name('settings.form-builder');
    Route::get('/settings/signup-form', [SettingController::class, 'signupForm'])->name('settings.signup-form');
    Route::post('/settings/signup-form', [SettingController::class, 'saveSignupForm'])->name('settings.signup-form.save');
    Route::get('/settings/settlements', [SettingController::class, 'settlements'])->name('settings.settlements');
    Route::post('/settings/settlements/{settlement}/confirm', [SettingController::class, 'confirmSettlement'])->name('settings.settlements.confirm');
    Route::get('/settings/user-complete-info', [SettingController::class, 'userCompleteInfo'])->name('settings.user-complete');
    Route::get('/settings/user-profile', [SettingController::class, 'userProfileForm'])->name('settings.user-profile');
    Route::get('/settings/change-password', [SettingController::class, 'changePasswordForm'])->name('settings.change-password');
});
