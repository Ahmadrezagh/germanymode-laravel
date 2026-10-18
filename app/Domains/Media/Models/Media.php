<?php

namespace App\Domains\Media\Models;

use App\Shared\Support\MediaUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Media extends Model
{
    public $timestamps = false;

    protected $table = 'media';

    protected $guarded = [];

    public function url(): ?string
    {
        return MediaUrl::url($this->path);
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(
            \App\Domains\Catalog\Models\Product::class,
            'model',
            'mediables',
            'media_id',
            'model_id'
        );
    }
}
