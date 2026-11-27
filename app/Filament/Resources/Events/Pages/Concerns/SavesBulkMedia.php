<?php

namespace App\Filament\Resources\Events\Pages\Concerns;

use App\Models\EventImage;

/**
 * The "upload several photos and videos at once" box on the event form: after the event is saved, each chosen
 * file becomes its own entry in "More photos & videos" (the type is worked out from the file), added at the end.
 */
trait SavesBulkMedia
{
    /** @var array<int, string> stored file paths waiting to be added */
    protected array $bulkMedia = [];

    protected function pullBulkMedia(array $data): array
    {
        $this->bulkMedia = array_values(array_filter((array) ($data['bulk_media'] ?? [])));
        unset($data['bulk_media']);

        return $data;
    }

    protected function storeBulkMedia(): void
    {
        if (! $this->bulkMedia) {
            return;
        }

        $event = $this->getRecord();
        $order = (int) $event->images()->reorder()->max('sort_order');

        foreach ($this->bulkMedia as $path) {
            $event->images()->create(['type' => EventImage::typeFor($path), 'path' => $path, 'sort_order' => ++$order]);
        }

        $this->bulkMedia = [];
    }
}
