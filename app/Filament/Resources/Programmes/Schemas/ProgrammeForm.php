<?php

namespace App\Filament\Resources\Programmes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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
                Repeater::make('modules')
                    ->label('Modules (shown on the programme page, one tab per semester)')
                    ->helperText('Add each module with its semester. Modules of the same semester appear together, in the order listed here.')
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->addActionLabel('Add module')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null)
                        ? 'Semester '.($state['semester'] ?? '?').' - '.$state['title']
                        : null)
                    ->schema([
                        Select::make('semester')
                            ->options(collect(range(1, 8))->mapWithKeys(fn ($n) => [$n => 'Semester '.$n])->all())
                            ->required(),
                        TextInput::make('title')
                            ->label('Module name')
                            ->required()
                            ->maxLength(200),
                    ])
                    ->columns(2),
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
