<?php

namespace App\Domains\Marketing\Models;

use App\Shared\Support\MediaUrl;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function imageUrl(): string
    {
        $path = trim((string) $this->image);
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, '//')) {
            return 'https:'.$path;
        }
        if (
            str_starts_with($path, '/uploads/')
            || str_starts_with($path, '/images/')
            || str_starts_with($path, 'uploads/')
            || str_starts_with($path, 'images/')
        ) {
            return asset(ltrim($path, '/'));
        }

        return MediaUrl::url($path) ?: asset(ltrim($path, '/'));
    }

    public function hasButton(): bool
    {
        return filled($this->button_url) && filled($this->button_title);
    }
}
