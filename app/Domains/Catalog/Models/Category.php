<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

#[Fillable(['name', 'parent_id'])]
class Category extends Model
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public static function megaMenuRoot(): ?self
    {
        return static::query()
            ->whereNull('parent_id')
            ->where('name', 'like', '%دسته%')
            ->orderBy('id')
            ->first()
            ?? static::query()->whereNull('parent_id')->orderBy('id')->first();
    }

    public static function megaMenuTree(): EloquentCollection
    {
        $root = static::megaMenuRoot();
        if (! $root) {
            return new EloquentCollection;
        }

        return static::query()
            ->where('parent_id', $root->id)
            ->with([
                'children' => fn ($q) => $q->orderBy('name')->with([
                    'children' => fn ($q2) => $q2->orderBy('name'),
                ]),
            ])
            ->orderBy('name')
            ->get();
    }

    public function descendantIds(): Collection
    {
        $all = static::query()->get(['id', 'parent_id'])->groupBy('parent_id');

        $walk = function (int $id) use (&$walk, $all): array {
            $ids = [$id];
            foreach ($all->get($id, collect()) as $child) {
                $ids = array_merge($ids, $walk((int) $child->id));
            }

            return $ids;
        };

        return collect($walk((int) $this->id));
    }
}
