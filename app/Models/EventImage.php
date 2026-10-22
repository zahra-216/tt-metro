<?php

namespace App\Models;

use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventImage extends Model
{
    use CleansUpFiles;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['path'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}