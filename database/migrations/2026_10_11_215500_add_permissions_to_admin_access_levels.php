<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_access_levels', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('is_blocked');
            $table->text('notes')->nullable()->after('permissions');
        });
    }

    public function down(): void
    {
        Schema::table('admin_access_levels', function (Blueprint $table) {
            $table->dropColumn(['permissions', 'notes']);
        });
    }
};
