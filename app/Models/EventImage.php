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

    /** "video" for an uploaded video file, otherwise "image". */
    public static function typeFor(string $path): string
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['mp4', 'webm'], true) ? 'video' : 'image';
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}