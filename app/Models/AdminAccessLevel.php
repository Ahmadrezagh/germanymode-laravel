<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminAccessLevel extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'access_level_id');
    }

    public function label(): string
    {
        return $this->is_blocked ? $this->name.' (دسترسی مسدود)' : $this->name;
    }

    public function permissionTitles(): array
    {
        $selected = collect($this->permissions ?? []);
        if ($selected->isEmpty()) {
            return [];
        }

        $map = collect(config('admin_permissions', []))
            ->flatMap(fn ($group) => $group['permissions'] ?? [])
            ->pluck('title', 'key');

        return $selected
            ->map(fn ($key) => $map[$key] ?? $key)
            ->values()
            ->all();
    }
}
