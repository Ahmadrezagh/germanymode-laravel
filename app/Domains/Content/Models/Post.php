<?php

namespace App\Domains\Content\Models;

use App\Domains\Interactions\Models\Comment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
