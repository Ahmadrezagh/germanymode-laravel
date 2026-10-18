<?php

namespace App\Domains\Marketing\Providers;

use Illuminate\Support\ServiceProvider;

class MarketingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Marketing is composed via SiteSettings admin controllers and Storefront reads.
    }
}
