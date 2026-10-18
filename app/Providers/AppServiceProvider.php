<?php

namespace App\Providers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'product' => Product::class,
            'post' => Post::class,
            // Legacy morph keys from pre-domain App\Models aliases
            'App\Models\Product' => Product::class,
            'App\Models\Post' => Post::class,
        ]);
    }
}
