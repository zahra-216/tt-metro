<?php

namespace App\Filament\Resources\StudentLifeItems\Pages;

use App\Filament\Resources\StudentLifeItems\StudentLifeItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentLifeItems extends ListRecords
{
    protected static string $resource = StudentLifeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
