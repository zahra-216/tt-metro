<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Events\Pages\Concerns\SavesBulkMedia;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    use SavesBulkMedia;

    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->pullBulkMedia($data);
    }

    protected function afterSave(): void
    {
        $added = count($this->bulkMedia);
        $this->storeBulkMedia();

        // show the new files in the list right away, and empty the upload box
        if ($added) {
            $this->getRecord()->unsetRelation('images');
            $this->fillForm();
        }
    }
}
