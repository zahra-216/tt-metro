<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Support\MediaFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                DatePicker::make('starts_on'),
                DatePicker::make('ends_on'),
                TextInput::make('location'),
                Textarea::make('excerpt')
                    ->rows(2)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Main image')
                    ->image()
                    ->directory('events')
                    ->helperText('Shown on the events list and as the first photo on the event page.'),
                Toggle::make('is_active')
                    ->default(true),
                ...MediaFields::make('images', 'events', 'the list below'),
            ]);
    }
}