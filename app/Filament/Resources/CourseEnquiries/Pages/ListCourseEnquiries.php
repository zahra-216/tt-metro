<?php

namespace App\Filament\Resources\CourseEnquiries\Pages;

use App\Filament\Resources\CourseEnquiries\CourseEnquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListCourseEnquiries extends ListRecords
{
    protected static string $resource = CourseEnquiryResource::class;
}
