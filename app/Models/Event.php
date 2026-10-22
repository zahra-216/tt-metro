<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use CleansUpFiles, HasSlug;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['image'];

    protected $casts = ['starts_on' => 'date', 'ends_on' => 'date'];

    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order');
    }
}
