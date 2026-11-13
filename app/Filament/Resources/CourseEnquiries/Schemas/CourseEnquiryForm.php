<?php

namespace App\Filament\Resources\CourseEnquiries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseEnquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('programme_title')->label('Programme')->disabled(),
                TextInput::make('name')->disabled(),
                TextInput::make('phone')->label('WhatsApp number')->disabled(),
                Textarea::make('message')->disabled()->rows(6)->columnSpanFull(),
                Toggle::make('is_read')->label('Marked as read'),
            ]);
    }
}
