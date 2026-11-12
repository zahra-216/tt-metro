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

    /** Faculties to show on the public site: switched on AND with at least one active programme. */
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)
            ->whereHas('programmes', fn ($q) => $q->where('is_active', true));
    }
}

