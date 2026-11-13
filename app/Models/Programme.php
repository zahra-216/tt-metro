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

    protected $casts = ['requirements' => 'array', 'modules' => 'array', 'is_featured' => 'boolean', 'is_active' => 'boolean'];

    /**
     * Modules grouped by semester for the programme page: [1 => ['Module', ...], 2 => [...]], semesters in order.
     *
     * @return array<int, array<int, string>>
     */
    public function modulesBySemester(): array
    {
        return collect($this->modules ?? [])
            ->filter(fn ($m) => filled($m['title'] ?? null) && filled($m['semester'] ?? null))
            ->groupBy(fn ($m) => (int) $m['semester'])
            ->sortKeys()
            ->map(fn ($group) => $group->pluck('title')->map(fn ($t) => trim($t))->values()->all())
            ->all();
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}

