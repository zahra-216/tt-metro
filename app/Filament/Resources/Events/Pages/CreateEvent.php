<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\Pages\Concerns\SavesBulkMedia;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use SavesBulkMedia;

    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->pullBulkMedia($data);
    }

    protected function afterCreate(): void
    {
        $this->storeBulkMedia();
    }
}
