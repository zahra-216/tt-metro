<?php

namespace App\Filament\Resources\GalleryAlbums\Schemas;

use App\Filament\Support\MediaFields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                Toggle::make('is_active')
                    ->default(true),
                ...MediaFields::make('items', 'gallery', 'the album'),
            ]);
    }
}