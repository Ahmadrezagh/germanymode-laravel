<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('color')->nullable();
            $table->string('model')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'color', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};
