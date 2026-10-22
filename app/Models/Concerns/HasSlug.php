<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Generates a unique URL slug from the title/name when none is given. */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (filled($model->slug)) {
                return;
            }
            $base = Str::slug($model->title ?? $model->name) ?: 'item';
            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $model->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}