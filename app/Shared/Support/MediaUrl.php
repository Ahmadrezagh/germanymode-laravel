<?php

namespace App\Shared\Support;

class MediaUrl
{
    public const BASE = 'https://panel123456.germanymode.com';

    public static function url(?string $path): ?string
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

        return self::BASE.'/'.ltrim($path, '/');
    }
}
