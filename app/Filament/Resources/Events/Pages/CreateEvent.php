<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Support\SavesBulkMedia;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use SavesBulkMedia;

    protected static string $resource = EventResource::class;

    protected function mediaRelation(): string
    {
        return 'images';
    }
}
