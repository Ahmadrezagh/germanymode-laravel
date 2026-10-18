<?php

namespace App\Domains\Marketing\Models;

use App\Shared\Support\MediaUrl;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'views' => 'integer',
        ];
    }

    public function profileImageUrl(): ?string
    {
        return MediaUrl::url($this->profile_image);
    }

    public function mediaUrl(): ?string
    {
        return MediaUrl::url($this->media_path);
    }

    public function linkImageUrl(): ?string
    {
        return MediaUrl::url($this->link_image);
    }

    public function isVideo(): bool
    {
        $path = strtolower((string) $this->media_path);

        return str_ends_with($path, '.mp4')
            || str_ends_with($path, '.webm')
            || str_ends_with($path, '.mov');
    }

    public function toViewerArray(): array
    {
        return [
            'id' => $this->id,
            'profile_name' => $this->profile_name,
            'profile_image' => $this->profileImageUrl(),
            'title' => $this->title,
            'short_text' => $this->short_text,
            'media' => $this->mediaUrl(),
            'is_video' => $this->isVideo(),
            'link_url' => $this->link_url,
            'link_title' => $this->link_title,
            'link_image' => $this->linkImageUrl(),
        ];
    }
}
