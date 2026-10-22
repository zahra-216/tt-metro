<?php

namespace App\Filament\Resources\GalleryAlbums\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GalleryAlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->rows(2),
                FileUpload::make('cover_image')
                    ->image()
                    ->directory('gallery')
                    ->helperText('Leave empty to use the first photo of the album.'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
                Repeater::make('items')
                    ->label('Photos & videos')
                    ->relationship()
                    ->orderColumn('sort_order')
                    ->columnSpanFull()
                    ->grid(2)
                    ->defaultItems(0)
                    ->addActionLabel('Add photo or video')
                    ->schema([
                        Select::make('type')
                            ->options(['image' => 'Photo', 'video' => 'Uploaded video (mp4)', 'youtube' => 'YouTube / Vimeo link'])
                            ->default('image')
                            ->live()
                            ->required(),
                        FileUpload::make('path')
                            ->label('File')
                            ->directory('gallery')
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