<?php

use App\Domains\AdminShell\Providers\AdminShellServiceProvider;
use App\Domains\Catalog\Providers\CatalogServiceProvider;
use App\Domains\Content\Providers\ContentServiceProvider;
use App\Domains\Identity\Providers\IdentityServiceProvider;
use App\Domains\Interactions\Providers\InteractionsServiceProvider;
use App\Domains\Marketing\Providers\MarketingServiceProvider;
use App\Domains\Media\Providers\MediaServiceProvider;
use App\Domains\Orders\Providers\OrdersServiceProvider;
use App\Domains\SiteSettings\Providers\SiteSettingsServiceProvider;
use App\Domains\Storefront\Providers\StorefrontServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CatalogServiceProvider::class,
    StorefrontServiceProvider::class,
    OrdersServiceProvider::class,
    ContentServiceProvider::class,
    MarketingServiceProvider::class,
    MediaServiceProvider::class,
    SiteSettingsServiceProvider::class,
    IdentityServiceProvider::class,
    InteractionsServiceProvider::class,
    AdminShellServiceProvider::class,
];
