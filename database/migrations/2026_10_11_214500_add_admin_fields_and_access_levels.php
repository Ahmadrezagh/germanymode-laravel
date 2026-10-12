<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_access_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_blocked')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('portal_id')->nullable()->unique()->after('id');
            $table->foreignId('access_level_id')->nullable()->after('role')->constrained('admin_access_levels')->nullOnDelete();
            $table->unsignedTinyInteger('admin_rank')->default(0)->after('access_level_id'); // 0..5 stars
            $table->string('page_link')->nullable()->after('admin_rank');
            $table->string('avatar')->nullable()->after('page_link');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('access_level_id');
            $table->dropColumn(['portal_id', 'admin_rank', 'page_link', 'avatar']);
        });
        Schema::dropIfExists('admin_access_levels');
    }
};
