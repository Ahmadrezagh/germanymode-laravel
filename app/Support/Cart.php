<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class Cart
{
    public const SESSION_KEY = 'shop_cart';

    public static function items(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public static function count(): int
    {
        return (int) collect(self::items())->sum('qty');
    }

    public static function subtotal(): int
    {
        return (int) collect(self::items())->sum(fn ($row) => ((int) $row['price']) * ((int) $row['qty']));
    }

    public static function add(Product $product, int $qty = 1): void
    {
        $qty = max(1, min(99, $qty));
        $items = self::items();
        $id = (string) $product->id;

        if (isset($items[$id])) {
            $items[$id]['qty'] = max(1, min(99, ((int) $items[$id]['qty']) + $qty));
        } else {
            $items[$id] = [
                'id' => $product->id,
                'title' => $product->title,
                'price' => (int) $product->price,
                'image' => $product->image,
                'url' => $product->url(),
                'qty' => $qty,
            ];
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public static function update(int $productId, int $qty): void
    {
        $items = self::items();
        $id = (string) $productId;

        if (! isset($items[$id])) {
            return;
        }

        if ($qty <= 0) {
            unset($items[$id]);
        } else {
            $items[$id]['qty'] = min(99, $qty);
        }

        Session::put(self::SESSION_KEY, $items);
    }

    public static function remove(int $productId): void
    {
        $items = self::items();
        unset($items[(string) $productId]);
        Session::put(self::SESSION_KEY, $items);
    }

    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
