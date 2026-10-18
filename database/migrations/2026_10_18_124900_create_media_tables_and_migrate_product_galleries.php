<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('path');
                $table->unique('path');
            });
        }

        if (! Schema::hasTable('mediables')) {
            Schema::create('mediables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
                $table->unsignedBigInteger('model_id');
                $table->string('model_type');
                $table->index(['model_type', 'model_id']);
            });
        }

        if (Schema::hasColumn('products', 'gallery_urls')) {
            $this->migrateProductGalleries();
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('gallery_urls');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'gallery_urls')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('gallery_urls')->nullable();
            });
        }

        Schema::dropIfExists('mediables');
        Schema::dropIfExists('media');
    }

    private function migrateProductGalleries(): void
    {
        DB::table('mediables')->delete();
        DB::table('media')->delete();

        $mediaByPath = [];
        $mediableRows = [];

        $products = DB::table('products')->orderBy('id')->select(['id', 'gallery_urls'])->get();

        foreach ($products as $product) {
            foreach ($this->decodeGallery($product->gallery_urls) as $path) {
                if (! isset($mediaByPath[$path])) {
                    $title = basename(parse_url($path, PHP_URL_PATH) ?: $path) ?: 'media';
                    $mediaByPath[$path] = (int) DB::table('media')->insertGetId([
                        'title' => $title,
                        'path' => $path,
                    ]);
                }

                $mediableRows[] = [
                    'media_id' => $mediaByPath[$path],
                    'model_id' => $product->id,
                    'model_type' => 'product',
                ];

                if (count($mediableRows) >= 500) {
                    DB::table('mediables')->insert($mediableRows);
                    $mediableRows = [];
                }
            }
        }

        if ($mediableRows !== []) {
            DB::table('mediables')->insert($mediableRows);
        }
    }

    /** @return list<string> */
    private function decodeGallery(mixed $raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }

        if (is_array($raw)) {
            $decoded = $raw;
        } else {
            $decoded = json_decode((string) $raw, true);
            if (! is_array($decoded)) {
                return [];
            }
        }

        return collect($decoded)
            ->map(fn ($path) => trim((string) $path))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
};
