<?php

namespace App\Domains\Catalog\Models;

use App\Domains\Media\Models\Media;
use App\Shared\Support\MediaUrl;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'title',
    'short_description',
    'regular_price',
    'discounted_price',
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
    public const MEDIA_BASE = MediaUrl::BASE;

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'regular_price' => 'integer',
            'discounted_price' => 'integer',
            'is_featured' => 'boolean',
            'is_special_discount' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'mediables', 'model_id', 'media_id')
            ->orderBy('mediables.id');
    }

    /**
     * Effective sell price:
     * discounted_price when it is > 0 and lower than regular_price, otherwise regular_price.
     */
    protected function price(): Attribute
    {
        return Attribute::get(function (): int {
            $regular = (int) ($this->regular_price ?? 0);
            $discounted = (int) ($this->discounted_price ?? 0);

            if ($discounted > 0 && $discounted < $regular) {
                return $discounted;
            }

            return $regular;
        });
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::get(function (): bool {
            $regular = (int) ($this->regular_price ?? 0);
            $discounted = (int) ($this->discounted_price ?? 0);

            return $discounted > 0 && $discounted < $regular;
        });
    }

    /** @return list<string> */
    protected function galleryUrls(): Attribute
    {
        return Attribute::get(function (): array {
            if ($this->relationLoaded('media')) {
                return $this->media->pluck('path')->all();
            }

            return $this->media()->pluck('media.path')->all();
        });
    }

    public function scopeWhereEffectivePriceGreaterThan(Builder $query, int $amount = 0): Builder
    {
        return $query->whereRaw(
            'CASE
                WHEN discounted_price > 0 AND discounted_price < COALESCE(regular_price, 0)
                THEN discounted_price
                ELSE COALESCE(regular_price, 0)
             END > ?',
            [$amount]
        );
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
        return MediaUrl::url($path);
    }

    protected function image(): Attribute
    {
        return Attribute::get(function () {
            $first = $this->relationLoaded('media')
                ? $this->media->first()
                : $this->media()->first();

            return $first?->url();
        });
    }

    public function galleryUrlsResolved(): array
    {
        $items = $this->relationLoaded('media')
            ? $this->media
            : $this->media()->get();

        return $items
            ->map(fn (Media $media) => $media->url())
            ->filter()
            ->values()
            ->all();
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

        return number_format((int) $this->regular_price).' تومان';
    }

    public function priceNumber(): string
    {
        return $this->price > 0 ? number_format($this->price) : '0';
    }

    public function oldPriceNumber(): ?string
    {
        return $this->has_discount ? number_format((int) $this->regular_price) : null;
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
