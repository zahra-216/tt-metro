<?php

namespace App\Filament\Support;

use App\Support\MediaFiles;

/**
 * After an event or album is saved, every file chosen in "Add photos & videos" and every link in the video link box
 * becomes its own entry, added at the end. Used with MediaFields. The page says which relation holds them.
 */
trait SavesBulkMedia
{
    /** @var array<int, array<string, mixed>> entries waiting to be added */
    protected array $bulkMedia = [];

    abstract protected function mediaRelation(): string;

    protected function pullBulkMedia(array $data): array
    {
        $this->bulkMedia = [];

        foreach (array_filter((array) ($data['bulk_media'] ?? [])) as $path) {
            $this->bulkMedia[] = ['type' => MediaFiles::typeFor($path), 'path' => $path];
        }

        foreach (MediaFiles::lines($data['bulk_links'] ?? null) as $line) {
            if ($link = MediaFiles::videoLink($line)) {
                $this->bulkMedia[] = ['type' => 'youtube', 'video_url' => $link];
            }
        }

        unset($data['bulk_media'], $data['bulk_links']);

        return $data;
    }

    protected function storeBulkMedia(): void
    {
        if (! $this->bulkMedia) {
            return;
        }

        $relation = $this->getRecord()->{$this->mediaRelation()}();
        $order = (int) (clone $relation)->reorder()->max('sort_order');

        foreach ($this->bulkMedia as $entry) {
            $this->getRecord()->{$this->mediaRelation()}()->create($entry + ['sort_order' => ++$order]);
        }

        $this->bulkMedia = [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->pullBulkMedia($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->pullBulkMedia($data);
    }

    protected function afterCreate(): void
    {
        $this->storeBulkMedia();
    }

    protected function afterSave(): void
    {
        $added = count($this->bulkMedia);
        $this->storeBulkMedia();

        // show the new entries in the list right away, and empty the boxes
        if ($added) {
            $this->getRecord()->unsetRelation($this->mediaRelation());
            $this->fillForm();
        }
    }
}
