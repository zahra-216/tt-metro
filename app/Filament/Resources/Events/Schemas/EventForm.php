<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
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
                FileUpload::make('bulk_media')
                    ->label('Upload several photos & videos at once')
                    ->helperText('Select or drag many files together (photos, MP4 or WEBM videos, up to 50 MB each). Wait until all are uploaded, then press Save: they are added to the list below.')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->panelLayout('grid')
                    ->directory('events')
                    ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                    ->maxSize(51200)
                    ->columnSpanFull(),
                Repeater::make('images')
                    ->label('More photos & videos')
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->columnSpanFull()
                    ->grid(2)
                    ->defaultItems(0)
                    ->addActionLabel('Add one photo, video or YouTube link')
                    ->schema([
                        Select::make('type')
                            ->options(['image' => 'Photo', 'video' => 'Uploaded video (mp4)', 'youtube' => 'YouTube / Vimeo link'])
                            ->default('image')
                            ->live()
                            ->required(),
                        FileUpload::make('path')
                            ->label('File')
                            ->directory('events')
                            ->visible(fn (Get $get) => $get('type') !== 'youtube')
                            ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                            ->maxSize(51200),
                        TextInput::make('video_url')
                            ->label('Video link')
                            ->url()
                            ->visible(fn (Get $get) => $get('type') === 'youtube'),
                        TextInput::make('caption'),
                    ]),
            ]);
    }
}