<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@germanymode.com'],
            [
                'name' => 'مدیر',
                'last_name' => 'سایت',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'admin',
            ]
        );

        $sourcePath = base_path('../app.db');
        if (! is_file($sourcePath)) {
            $this->command?->warn("Source DB not found: {$sourcePath}");

            return;
        }

        $source = new \PDO('sqlite:'.$sourcePath);
        $source->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        DB::statement('PRAGMA foreign_keys = OFF');
        DB::table('product_models')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::statement('DELETE FROM sqlite_sequence WHERE name IN ("categories","products","product_models")');

        $now = now()->toDateTimeString();

        $categories = $source->query('SELECT id, name, parent_id FROM categories ORDER BY id')->fetchAll(\PDO::FETCH_ASSOC);
        $categoryRows = [];
        foreach ($categories as $row) {
            $categoryRows[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'parent_id' => $row['parent_id'] !== null ? (int) $row['parent_id'] : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($categoryRows, 100) as $chunk) {
            DB::table('categories')->insert($chunk);
        }

        $products = $source->query(
            'SELECT id, title, short_description, gallery_urls, price_without_discount, price, specifications, sitemap_id, category_id FROM products ORDER BY id'
        )->fetchAll(\PDO::FETCH_ASSOC);

        $productRows = [];
        foreach ($products as $row) {
            $gallery = $row['gallery_urls'];
            if ($gallery && ! json_validate($gallery)) {
                $decoded = json_decode($gallery, true);
                $gallery = json_encode(is_array($decoded) ? $decoded : [], JSON_UNESCAPED_UNICODE);
            }

            $productRows[] = [
                'id' => (int) $row['id'],
                'title' => $row['title'],
                'short_description' => $row['short_description'],
                'gallery_urls' => $gallery ?: '[]',
                'price_without_discount' => $row['price_without_discount'],
                'price' => (int) ($row['price'] ?? 0),
                'specifications' => $row['specifications'],
                'sitemap_id' => $row['sitemap_id'],
                'category_id' => $row['category_id'] !== null ? (int) $row['category_id'] : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($productRows, 50) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        $models = $source->query('SELECT product_id, color, model FROM product_models ORDER BY id')->fetchAll(\PDO::FETCH_ASSOC);
        $modelRows = [];
        foreach ($models as $row) {
            $modelRows[] = [
                'product_id' => (int) $row['product_id'],
                'color' => $row['color'],
                'model' => $row['model'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($modelRows, 100) as $chunk) {
            DB::table('product_models')->insert($chunk);
        }

        DB::statement('PRAGMA foreign_keys = ON');

        $this->command?->info(sprintf(
            'Imported %d categories, %d products, %d models. Admin: admin@germanymode.com / admin123',
            count($categoryRows),
            count($productRows),
            count($modelRows)
        ));
    }
}
