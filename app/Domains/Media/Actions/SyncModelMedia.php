<?php

namespace App\Domains\Media\Actions;

use App\Domains\Media\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SyncModelMedia
{
    /**
     * Replace all media attachments for a model with the given ordered paths.
     *
     * @param  list<string>  $paths
     */
    public function handle(Model $model, array $paths): void
    {
        $paths = collect($paths)
            ->map(fn ($path) => trim((string) $path))
            ->filter()
            ->values()
            ->all();

        DB::transaction(function () use ($model, $paths) {
            DB::table('mediables')
                ->where('model_type', $model->getMorphClass())
                ->where('model_id', $model->getKey())
                ->delete();

            foreach ($paths as $index => $path) {
                $media = Media::query()->firstOrCreate(
                    ['path' => $path],
                    ['title' => basename(parse_url($path, PHP_URL_PATH) ?: $path) ?: 'media-'.($index + 1)]
                );

                DB::table('mediables')->insert([
                    'media_id' => $media->id,
                    'model_id' => $model->getKey(),
                    'model_type' => $model->getMorphClass(),
                ]);
            }
        });
    }
}
