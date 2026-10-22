<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }
}

