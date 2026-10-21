<?php

namespace App\Console\Commands;

use App\Domains\Catalog\Models\Product;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Throwable;

class ReindexProducts extends Command
{
    protected $signature = 'products:reindex
                            {--chunk=500 : Products per bulk request}';

    protected $description = 'Index PostgreSQL products into Elasticsearch';

    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');

        if ($chunkSize < 1 || $chunkSize > 5000) {
            $this->error('Chunk size must be between 1 and 5000.');

            return self::FAILURE;
        }

        $config = config('services.elasticsearch');

        try {
            $client = ClientBuilder::create()
                ->setHosts([$config['host']])
                ->setBasicAuthentication(
                    $config['username'],
                    $config['password']
                )
                ->setCABundle($config['ca'])
                ->build();

            if (! $client->indices()
                ->exists(['index' => 'products_v1'])
                ->asBool()) {
                $this->error(
                    'Index products_v1 does not exist. Create it first.'
                );

                return self::FAILURE;
            }

            $total = Product::query()->count();

            if ($total === 0) {
                $this->info('No products found.');

                return self::SUCCESS;
            }

            $this->info("Starting indexing of {$total} products...");
            $this->output->progressStart($total);

            $indexed = 0;
            $failed = 0;
            $errorSamples = [];

            Product::query()
                ->select([
                    'id',
                    'title',
                    'short_description',
                    'specifications',
                    'regular_price',
                    'discounted_price',
                    'sitemap_id',
                    'category_id',
                    'brand_id',
                    'warehouse_id',
                    'status',
                    'is_featured',
                    'is_special_discount',
                    'sort_order',
                    'meta',
                    'created_at',
                    'updated_at',
                ])
                ->chunkById($chunkSize, function ($products) use (
                    $client,
                    &$indexed,
                    &$failed,
                    &$errorSamples
                ) {
                    $body = [];

                    foreach ($products as $product) {
                        $body[] = [
                            'index' => [
                                '_index' => 'products_v1',
                                '_id' => (string) $product->id,
                            ],
                        ];

                        $body[] = [
                            'id' => (string) $product->id,
                            'title' => $product->title,
                            'short_description' => $product->short_description,
                            'specifications' => $product->specifications,
                            'regular_price' => $product->regular_price,
                            'discounted_price' => $product->discounted_price,
                            'sitemap_id' => $product->sitemap_id,
                            'category_id' => $product->category_id,
                            'brand_id' => $product->brand_id,
                            'warehouse_id' => $product->warehouse_id,
                            'status' => $product->status,
                            'is_featured' => $product->is_featured,
                            'is_special_discount' => $product->is_special_discount,
                            'sort_order' => $product->sort_order,
                            'meta' => $product->meta,
                            'created_at' => $product->created_at?->toISOString(),
                            'updated_at' => $product->updated_at?->toISOString(),
                        ];
                    }

                    $response = $client->bulk([
                        'body' => $body,
                    ])->asArray();

                    foreach ($response['items'] ?? [] as $item) {
                        $result = $item['index'] ?? [];

                        if (isset($result['error'])) {
                            $failed++;

                            if (count($errorSamples) < 5) {
                                $errorSamples[] = [
                                    'id' => $result['_id'] ?? 'unknown',
                                    'error' => $result['error'],
                                ];
                            }
                        } else {
                            $indexed++;
                        }
                    }

                    $this->output->progressAdvance($products->count());
                });

            $this->output->progressFinish();

            // Make indexed documents searchable immediately.
            $client->indices()->refresh([
                'index' => 'products_v1',
            ]);

            $this->newLine(2);
            $this->info("Successfully indexed: {$indexed}");
            $this->info("Failed documents: {$failed}");

            if ($failed > 0) {
                $this->error('Sample indexing errors:');
                $this->line(json_encode(
                    $errorSamples,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
                ));

                return self::FAILURE;
            }

            $this->info('Reindex completed successfully.');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->newLine();
            $this->error('Reindex failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}

