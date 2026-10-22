<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $guarded = [];

    protected $casts = ['date_of_birth' => 'date'];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}

