<?php

namespace App\Console\Commands;

use App\Domains\Identity\Models\User;
use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Models\OrderItem;
use App\Domains\Orders\Models\Settlement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportScrapedOrders extends Command
{
    protected $signature = 'orders:import-scrape
        {--orders= : Path to orders_scrape.json}
        {--settlements= : Path to settlements_scrape.json}';

    protected $description = 'Import scraped panel orders + settlements into the database';

    public function handle(): int
    {
        $ordersPath = $this->option('orders')
            ?: storage_path('app/orders_scrape.json');
        $settlementsPath = $this->option('settlements')
            ?: storage_path('app/settlements_scrape.json');

        if (! is_file($ordersPath)) {
            $this->error("Missing orders file: {$ordersPath}");

            return self::FAILURE;
        }

        $orders = json_decode(file_get_contents($ordersPath), true) ?: [];
        $settlements = is_file($settlementsPath)
            ? (json_decode(file_get_contents($settlementsPath), true) ?: [])
            : [];

        $portalToUser = User::query()
            ->whereNotNull('portal_id')
            ->pluck('id', 'portal_id');

        $orderCount = 0;
        $itemCount = 0;

        DB::transaction(function () use ($orders, $portalToUser, &$orderCount, &$itemCount) {
            foreach ($orders as $row) {
                $factor = (string) ($row['factor_no'] ?? '');
                if ($factor === '') {
                    continue;
                }

                $portalId = $row['portal_id'] ?? null;
                $userId = $portalId ? ($portalToUser[$portalId] ?? null) : null;

                $order = Order::query()->updateOrCreate(
                    ['factor_no' => $factor],
                    [
                        'portal_id' => $portalId,
                        'user_id' => $userId,
                        'buyer_name' => $row['buyer_name'] ?? null,
                        'phone' => $row['phone'] ?? null,
                        'fax' => $row['fax'] ?? null,
                        'postal_code' => $row['postal_code'] ?? null,
                        'shipping_address' => $row['shipping_address'] ?? null,
                        'purchased_at' => $row['purchased_at'] ?? null,
                        'issued_at' => $row['issued_at'] ?? null,
                        'amount' => (int) ($row['amount'] ?? 0),
                        'shipping_amount' => (int) ($row['shipping_amount'] ?? 0),
                        'total_amount' => (int) ($row['total_amount'] ?? $row['amount'] ?? 0),
                        'status' => $row['status'] ?? 'new',
                        'is_confirmed' => (bool) ($row['is_confirmed'] ?? false),
                        'is_prepared' => (bool) ($row['is_prepared'] ?? false),
                        'is_left_warehouse' => (bool) ($row['is_left_warehouse'] ?? false),
                        'is_shipped' => (bool) ($row['is_shipped'] ?? false),
                        'detail_fetched' => (bool) ($row['detail_fetched'] ?? false),
                        'meta' => [
                            'source' => $row['source'] ?? null,
                        ],
                    ]
                );
                $orderCount++;

                $items = $row['items'] ?? [];
                if (is_array($items) && $items !== []) {
                    $order->items()->delete();
                    foreach ($items as $item) {
                        OrderItem::query()->create([
                            'order_id' => $order->id,
                            'line_no' => (int) ($item['line_no'] ?? 1),
                            'title' => $item['title'] ?? null,
                            'specs' => $item['specs'] ?? null,
                            'qty' => $item['qty'] ?? null,
                            'unit_price' => (int) ($item['unit_price'] ?? 0),
                            'line_total' => (int) ($item['line_total'] ?? 0),
                        ]);
                        $itemCount++;
                    }
                }
            }
        });

        $settleCount = 0;
        DB::transaction(function () use ($settlements, $portalToUser, &$settleCount) {
            foreach ($settlements as $row) {
                $factor = (string) ($row['factor_no'] ?? $row['invoice_no'] ?? '');
                if ($factor === '') {
                    continue;
                }
                $portalId = $row['portal_id'] ?? null;
                $userId = $portalId ? ($portalToUser[$portalId] ?? null) : null;

                Settlement::query()->updateOrCreate(
                    [
                        'invoice_no' => $factor,
                        'paid_at_jalali' => $row['paid_at_jalali'] ?? null,
                        'deposit_ref' => $row['deposit_ref'] ?? null,
                    ],
                    [
                        'factor_no' => $factor,
                        'user_id' => $userId,
                        'portal_id' => $portalId,
                        'buyer_name' => $row['buyer_name'] ?? null,
                        'amount' => (int) ($row['amount'] ?? 0),
                        'is_confirmed' => (bool) ($row['is_confirmed'] ?? false),
                        'is_seen' => (bool) ($row['is_seen'] ?? false),
                        'payment_note' => $row['payment_note'] ?? null,
                        'meta' => $row,
                    ]
                );
                $settleCount++;
            }
        });

        $this->info("Imported orders={$orderCount} items={$itemCount} settlements={$settleCount}");

        return self::SUCCESS;
    }
}
