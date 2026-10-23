<?php

namespace App\Filament\Resources\Programmes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgrammeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faculty_id')
                    ->relationship('faculty', 'name'),
                TextInput::make('title')
                    ->required(),
                Select::make('level')
                    ->options(['Diploma' => 'Diploma', 'HND' => 'HND', 'Degree' => 'Degree', 'Top-up' => 'Top-up', 'Postgraduate Diploma' => 'Postgraduate Diploma', 'Other' => 'Other'])
                    ->default('Diploma')
                    ->required(),
                TextInput::make('duration'),
                TextInput::make('awarding_body'),
                TagsInput::make('requirements'),
                FileUpload::make('brochure')->directory('brochures')->acceptedFileTypes(['application/pdf']),
                Textarea::make('summary')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_featured'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
