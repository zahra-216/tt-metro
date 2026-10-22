<?php

namespace App\Filament\Resources\StudentLifeItems\Pages;

use App\Filament\Resources\StudentLifeItems\StudentLifeItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentLifeItem extends EditRecord
{
    protected static string $resource = StudentLifeItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
