<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    use CleansUpFiles, HasSlug;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['cover_image'];

    protected static function booted(): void
    {
        // a new album goes to the top of the list (and of the website); the admin can drag it elsewhere
        static::creating(function (GalleryAlbum $album) {
            if (! $album->sort_order) {
                static::query()->increment('sort_order');
                $album->sort_order = 1;
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }
}

