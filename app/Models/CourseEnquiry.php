<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnquiry extends Model
{
    protected $guarded = [];

    protected $casts = ['is_read' => 'boolean'];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}
