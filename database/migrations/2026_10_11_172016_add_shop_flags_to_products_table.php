<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('category_id');
            $table->boolean('is_special_discount')->default(false)->after('is_featured');
            $table->unsignedInteger('sort_order')->default(0)->after('is_special_discount');
            $table->string('status')->default('موجود')->after('sort_order');
            $table->foreignId('warehouse_id')->nullable()->after('status')->constrained('warehouses')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->after('warehouse_id')->constrained('brands')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_id');
            $table->dropConstrainedForeignId('brand_id');
            $table->dropColumn(['is_featured', 'is_special_discount', 'sort_order', 'status']);
        });
    }
};
