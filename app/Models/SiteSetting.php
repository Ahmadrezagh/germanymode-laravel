<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $guarded = [];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $all = Cache::remember('site_settings_map', 60, function () {
            return static::query()->pluck('value', 'key')->all();
        });

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $value = $all[$key];
        $decoded = json_decode((string) $value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    public static function setValue(string $key, mixed $value): void
    {
        $stored = is_array($value) || is_object($value)
            ? json_encode($value, JSON_UNESCAPED_UNICODE)
            : (string) $value;

        static::query()->updateOrCreate(['key' => $key], ['value' => $stored]);
        Cache::forget('site_settings_map');
    }
}
