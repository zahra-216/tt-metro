<?php

namespace App\Filament\Resources\CourseEnquiries\Pages;

use App\Filament\Resources\CourseEnquiries\CourseEnquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourseEnquiry extends EditRecord
{
    protected static string $resource = CourseEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
