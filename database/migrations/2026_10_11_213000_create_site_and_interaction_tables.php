<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 20)->nullable()->after('email');
            $table->string('last_name')->nullable()->after('name');
            $table->string('phone', 20)->nullable();
            $table->string('workplace_phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('national_code', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->string('role')->default('customer'); // admin|staff|customer
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('blog'); // blog|gallery|page
            $table->string('slug')->nullable();
            $table->string('title');
            $table->longText('body')->nullable();
            $table->string('author')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['type', 'is_hidden']);
        });

        Schema::create('quick_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });

        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('profile_name');
            $table->string('profile_image')->nullable();
            $table->string('title')->nullable();
            $table->string('media_path')->nullable();
            $table->string('short_text')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_image')->nullable();
            $table->string('link_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('mobile_footer_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->string('original_url');
            $table->string('code', 40)->unique();
            $table->unsignedInteger('visits')->default(0);
            $table->timestamps();
        });

        Schema::create('url_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('old_url');
            $table->string('new_url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('body');
            $table->boolean('is_seen')->default(false);
            $table->boolean('needs_reply')->default(true);
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('commentable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->text('body');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->string('status', 30)->default('open'); // open|answered|closed
            $table->string('priority', 20)->default('normal');
            $table->timestamps();
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_staff')->default(false);
            $table->timestamps();
        });

        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('votes')->default(0);
            $table->timestamps();
        });

        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->date('visited_on');
            $table->string('path')->nullable();
            $table->unsignedInteger('hits')->default(0);
            $table->unsignedInteger('unique_hits')->default(0);
            $table->timestamps();
            $table->unique(['visited_on', 'path']);
        });

        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('buyer_name')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_seen')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('settlements');
        Schema::dropIfExists('visits');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('url_redirects');
        Schema::dropIfExists('short_links');
        Schema::dropIfExists('mobile_footer_items');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('quick_links');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('site_settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mobile', 'last_name', 'phone', 'workplace_phone', 'fax',
                'national_code', 'postal_code', 'birth_date', 'province', 'city',
                'address', 'notes', 'is_blocked', 'role',
            ]);
        });
    }
};
