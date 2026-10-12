<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController as ShopAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController as StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('shop.home');
Route::get('/search', [StorefrontController::class, 'search'])->name('shop.search');
Route::get('/search/suggest', [StorefrontController::class, 'suggest'])->name('shop.search.suggest');
Route::get('/category/{category}', [StorefrontController::class, 'category'])->name('shop.category');
Route::get('/product/{product}', [StorefrontController::class, 'product'])->name('shop.product');
Route::get('/Product/{slug}/{product?}', [StorefrontController::class, 'productBySlug'])->name('shop.product.slug');

Route::get('/login', [ShopAuthController::class, 'showLogin'])->name('shop.login');
Route::post('/login', [ShopAuthController::class, 'login'])->name('shop.login.submit');
Route::get('/register', [ShopAuthController::class, 'showRegister'])->name('shop.register');
Route::post('/register', [ShopAuthController::class, 'register'])->name('shop.register.submit');
Route::post('/logout', [ShopAuthController::class, 'logout'])->name('shop.logout');

Route::get('/cart', [CartController::class, 'index'])->name('shop.cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('shop.cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('shop.cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('shop.cart.remove');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

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

        Route::get('/orders/new', [OrderController::class, 'newest'])->name('orders.new');
        Route::get('/orders/done', [OrderController::class, 'done'])->name('orders.done');

        // مدیریت صفحات و منوها
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

        // تنظیمات سایت / مدیریت سایت
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

        // تعاملات سایت
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/interactions/admins', [InteractionController::class, 'admins'])->name('interactions.admins');
        Route::post('/interactions/admins', [InteractionController::class, 'storeAdmin'])->name('interactions.admins.store');
        Route::put('/interactions/admins/{user}', [InteractionController::class, 'updateAdmin'])->name('interactions.admins.update');
        Route::post('/interactions/admins/{user}/block', [InteractionController::class, 'toggleBlockAdmin'])->name('interactions.admins.block');
        Route::delete('/interactions/admins/{user}', [InteractionController::class, 'destroyAdmin'])->name('interactions.admins.destroy');
        Route::get('/interactions/access-levels', [InteractionController::class, 'accessLevels'])->name('interactions.access-levels');
        Route::post('/interactions/access-levels', [InteractionController::class, 'storeAccessLevel'])->name('interactions.access-levels.store');
        Route::put('/interactions/access-levels/{level}', [InteractionController::class, 'updateAccessLevel'])->name('interactions.access-levels.update');
        Route::post('/interactions/access-levels/{level}/block', [InteractionController::class, 'toggleBlockAccessLevel'])->name('interactions.access-levels.block');
        Route::delete('/interactions/access-levels/{level}', [InteractionController::class, 'destroyAccessLevel'])->name('interactions.access-levels.destroy');
        Route::get('/interactions/comments', [InteractionController::class, 'comments'])->name('interactions.comments');
        Route::post('/interactions/comments/{comment}/approve', [InteractionController::class, 'approveComment'])->name('interactions.comments.approve');
        Route::delete('/interactions/comments/{comment}', [InteractionController::class, 'destroyComment'])->name('interactions.comments.destroy');
        Route::get('/interactions/visits', [InteractionController::class, 'visits'])->name('interactions.visits');
        Route::get('/interactions/polls', [InteractionController::class, 'polls'])->name('interactions.polls');
        Route::post('/interactions/polls', [InteractionController::class, 'storePoll'])->name('interactions.polls.store');
        Route::delete('/interactions/polls/{poll}', [InteractionController::class, 'destroyPoll'])->name('interactions.polls.destroy');
        Route::get('/interactions/tickets', [InteractionController::class, 'tickets'])->name('interactions.tickets');
        Route::post('/interactions/tickets/{ticket}/reply', [InteractionController::class, 'replyTicket'])->name('interactions.tickets.reply');
        Route::post('/interactions/tickets/{ticket}/close', [InteractionController::class, 'closeTicket'])->name('interactions.tickets.close');
        Route::get('/interactions/profile', [InteractionController::class, 'editProfile'])->name('interactions.profile');
        Route::post('/interactions/profile', [InteractionController::class, 'updateProfile'])->name('interactions.profile.save');

        Route::get('/p/{page}', [PageController::class, 'show'])->name('pages.show');
    });
});
