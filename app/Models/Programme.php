<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Programme extends Model
{
    use CleansUpFiles, HasSlug;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['image', 'brochure'];

    protected $casts = ['requirements' => 'array', 'is_featured' => 'boolean', 'is_active' => 'boolean'];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}

