<?php

namespace App\Models\Concerns;

use App\Models\GalleryItem;
use Illuminate\Support\Facades\Storage;

/**
 * Deletes uploaded files that are no longer used: the old file when a field is replaced or cleared,
 * and every file when the record is deleted. A file still used by another record is left alone.
 * Models list their file columns in $fileFields.
 */
trait CleansUpFiles
{
    protected static function bootCleansUpFiles(): void
    {
        static::updated(function ($model) {
            foreach ($model->fileFields as $field) {
                if ($model->wasChanged($field)) {
                    $model->deleteIfUnused($model->getOriginal($field));
                }
            }
        });

        static::deleted(function ($model) {
            foreach ($model->fileFields as $field) {
                $model->deleteIfUnused($model->getAttribute($field));
            }
        });
    }

    protected function deleteIfUnused(?string $path): void
    {
        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return;
        }

        foreach ($this->fileFields as $field) {
            if (static::where($field, $path)->where($this->getKeyName(), '!=', $this->getKey())->exists()) {
                return;
            }
        }
        // Album covers are normally one of the album's own photos.
        if (GalleryItem::where('path', $path)->exists() || \App\Models\GalleryAlbum::where('cover_image', $path)->where($this->getKeyName(), '!=', $this->getKey())->exists()) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}