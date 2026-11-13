<?php

namespace App\Filament\Resources\CourseEnquiries;

use App\Filament\Resources\CourseEnquiries\Pages\EditCourseEnquiry;
use App\Filament\Resources\CourseEnquiries\Pages\ListCourseEnquiries;
use App\Filament\Resources\CourseEnquiries\Schemas\CourseEnquiryForm;
use App\Filament\Resources\CourseEnquiries\Tables\CourseEnquiriesTable;
use App\Models\CourseEnquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourseEnquiryResource extends Resource
{
    protected static ?string $model = CourseEnquiry::class;

    protected static ?string $modelLabel = 'course enquiry';

    protected static ?string $pluralModelLabel = 'Course enquiries';

    protected static string|\UnitEnum|null $navigationGroup = 'Inbox';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function canCreate(): bool
    {
        return false; // they come from the website
    }

    public static function getNavigationBadge(): ?string
    {
        $unread = CourseEnquiry::where('is_read', false)->count();

        return $unread ? (string) $unread : null;
    }

    public static function form(Schema $schema): Schema
    {
        return CourseEnquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseEnquiriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseEnquiries::route('/'),
            'edit' => EditCourseEnquiry::route('/{record}/edit'),
        ];
    }
}
