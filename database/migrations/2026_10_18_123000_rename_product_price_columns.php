<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'price_without_discount') && ! Schema::hasColumn('products', 'regular_price')) {
                $table->renameColumn('price_without_discount', 'regular_price');
            }

            if (Schema::hasColumn('products', 'price') && ! Schema::hasColumn('products', 'discounted_price')) {
                $table->renameColumn('price', 'discounted_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'regular_price') && ! Schema::hasColumn('products', 'price_without_discount')) {
                $table->renameColumn('regular_price', 'price_without_discount');
            }

            if (Schema::hasColumn('products', 'discounted_price') && ! Schema::hasColumn('products', 'price')) {
                $table->renameColumn('discounted_price', 'price');
            }
        });
    }
};
