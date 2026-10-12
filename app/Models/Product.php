<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'short_description',
    'gallery_urls',
    'price_without_discount',
    'price',
    'specifications',
    'sitemap_id',
    'category_id',
    'is_featured',
    'is_special_discount',
    'sort_order',
    'status',
    'warehouse_id',
    'brand_id',
    'meta',
])]
class Product extends Model
{
    public const MEDIA_BASE = 'https://panel123456.germanymode.com';

    protected function casts(): array
    {
        return [
            'gallery_urls' => 'array',
            'meta' => 'array',
            'price' => 'integer',
            'price_without_discount' => 'integer',
            'is_featured' => 'boolean',
            'is_special_discount' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function metaGet(string $key, mixed $default = null): mixed
    {
        return data_get($this->meta ?? [], $key, $default);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(ProductModel::class);
    }

    public function slug(): string
    {
        $title = preg_replace('/\s+/u', '-', trim((string) $this->title)) ?? '';
        $slug = preg_replace('/[^\p{L}\p{N}\-_]+/u', '', $title) ?? '';
        $slug = trim($slug, '-_');

        return $slug !== '' ? $slug : (string) $this->id;
    }

    public function url(): string
    {
        return route('shop.product.slug', ['slug' => $this->slug(), 'product' => $this->id]);
    }

    public static function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '//')) {
            return 'https:'.$path;
        }

        return self::MEDIA_BASE.'/'.ltrim($path, '/');
    }

    protected function image(): Attribute
    {
        return Attribute::get(function () {
            $gallery = $this->gallery_urls ?? [];
            $first = is_array($gallery) && $gallery !== [] ? $gallery[0] : null;

            return self::mediaUrl($first);
        });
    }

    public function galleryUrlsResolved(): array
    {
        return collect($this->gallery_urls ?? [])
            ->map(fn ($url) => self::mediaUrl((string) $url))
            ->filter()
            ->values()
            ->all();
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::get(function () {
            return $this->price_without_discount
                && $this->price > 0
                && $this->price_without_discount > $this->price;
        });
    }

    public function formattedPrice(): string
    {
        if ($this->price <= 0) {
            return 'تماس بگیرید';
        }

        return number_format($this->price).' تومان';
    }

    public function formattedOldPrice(): ?string
    {
        if (! $this->has_discount) {
            return null;
        }

        return number_format($this->price_without_discount).' تومان';
    }

    public function priceNumber(): string
    {
        return $this->price > 0 ? number_format($this->price) : '0';
    }

    public function oldPriceNumber(): ?string
    {
        return $this->has_discount ? number_format($this->price_without_discount) : null;
    }

    /**
     * @return array{price: ?string, price_value: ?string, url: ?string, body: string}
     */
    public static function extractAmazonMeta(?string $text): array
    {
        $text = trim((string) $text);
        $price = null;
        $priceValue = null;
        $url = null;

        if ($text === '') {
            return [
                'price' => null,
                'price_value' => null,
                'url' => null,
                'body' => '',
            ];
        }

        if (preg_match('#https?://(?:www\.)?amazon\.[^\s<>"\']+#iu', $text, $match)) {
            $url = rtrim($match[0], '.,);]');
            $text = str_replace($match[0], '', $text);
        }

        $text = preg_replace('/^\s*لینک\s*آمازون\s*$/mu', '', $text) ?? $text;

        if (preg_match('/^\s*(قیمت[^\n]*?(\d+(?:[.,]\d+)?)\s*€?)\s*$/mu', $text, $match)) {
            $price = trim($match[1]);
            $priceValue = str_replace(',', '.', $match[2]).'€';
            $text = str_replace($match[0], '', $text);
        }

        $body = trim(preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text);

        return [
            'price' => $price,
            'price_value' => $priceValue,
            'url' => $url,
            'body' => $body,
        ];
    }
}
