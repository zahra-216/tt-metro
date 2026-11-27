<?php

namespace App\Filament\Support;

use App\Support\MediaFiles;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

/**
 * Photos and videos on events and gallery albums. Adding is always in bulk (many files, and many video links);
 * the list underneath only manages what is already there (order, captions, replace, delete).
 */
class MediaFields
{
    /** @return array<int, \Filament\Schemas\Components\Component> */
    public static function make(string $relationship, string $directory, string $where): array
    {
        return [
            FileUpload::make('bulk_media')
                ->label('Add photos & videos')
                ->helperText("Select or drag many files together (photos, MP4 or WEBM videos, up to 50 MB each). Wait until all show \"Upload complete\", then press Save: they are added to {$where}.")
                ->multiple()
                ->reorderable()
                ->appendFiles()
                ->panelLayout('grid')
                ->directory($directory)
                ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                ->maxSize(51200)
                ->columnSpanFull(),

            Textarea::make('bulk_links')
                ->label('Add YouTube or Vimeo videos (optional)')
                ->placeholder("https://youtu.be/...\nhttps://vimeo.com/...")
                ->helperText('Paste one link per line. They are added when you press Save.')
                ->rows(3)
                ->rule(fn (): Closure => function (string $attribute, $value, Closure $fail) {
                    foreach (MediaFiles::lines($value) as $line) {
                        if (! MediaFiles::videoLink($line)) {
                            $fail('This is not a YouTube or Vimeo link: '.$line);

                            return;
                        }
                    }
                })
                ->columnSpanFull(),

            Repeater::make($relationship)
                ->label('Added photos & videos (drag to change the order, add captions or delete)')
                ->relationship()
                ->orderColumn('sort_order')
                ->columnSpanFull()
                ->grid(2)
                ->defaultItems(0)
                ->addable(false)
                ->hiddenOn('create')
                // a replaced file may be of the other kind (photo or video)
                ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                    if (($data['type'] ?? '') !== 'youtube' && ! empty($data['path'])) {
                        $data['type'] = MediaFiles::typeFor($data['path']);
                    }

                    return $data;
                })
                ->schema([
                    Hidden::make('type'),
                    FileUpload::make('path')
                        ->label('File')
                        ->directory($directory)
                        ->visible(fn (Get $get) => $get('type') !== 'youtube')
                        ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                        ->maxSize(51200),
                    TextInput::make('video_url')
                        ->label('Video link')
                        ->url()
                        ->visible(fn (Get $get) => $get('type') === 'youtube'),
                    TextInput::make('caption'),
                ]),
        ];
    }
}
