<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\CleansUpFiles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use CleansUpFiles, HasSlug;

    protected $guarded = [];

    /** @var string[] */
    public array $fileFields = ['image'];

    protected $casts = ['starts_on' => 'date', 'ends_on' => 'date'];

    /** Text imported from the old website can hold codes such as "&amp;" - give back the real character. */
    protected function excerpt(): Attribute
    {
        return Attribute::get(fn (?string $value) => $value === null ? null : html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order');
    }
}
