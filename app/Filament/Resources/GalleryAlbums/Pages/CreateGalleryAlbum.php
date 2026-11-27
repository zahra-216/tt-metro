<?php

namespace App\Filament\Resources\GalleryAlbums\Pages;

use App\Filament\Resources\GalleryAlbums\GalleryAlbumResource;
use App\Filament\Support\SavesBulkMedia;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryAlbum extends CreateRecord
{
    use SavesBulkMedia;

    protected static string $resource = GalleryAlbumResource::class;

    protected function mediaRelation(): string
    {
        return 'items';
    }
}
