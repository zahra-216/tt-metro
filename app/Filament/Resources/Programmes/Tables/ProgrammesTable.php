<?php

namespace App\Filament\Resources\Programmes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProgrammesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('')->square()->size(48),
                TextColumn::make('title')->searchable()->sortable()->weight('medium')->wrap(),
                TextColumn::make('level')->badge()->sortable(),
                TextColumn::make('faculty.name')->label('Faculty')->sortable()->toggleable(),
                TextColumn::make('duration')->toggleable(),
                IconColumn::make('is_featured')->label('Featured')->boolean()->toggleable(),
                IconColumn::make('is_active')->label('Active')->boolean()->toggleable(),
            ])
            ->defaultSort('title')
            ->searchPlaceholder('Search programmes…')
            ->filters([
                SelectFilter::make('level')->options([
                    'Diploma' => 'Diploma', 'HND' => 'HND', 'Top-up' => 'Top-up', 'Degree' => 'Degree',
                    'Postgraduate Diploma' => 'Postgraduate Diploma', 'Masters' => 'Masters', 'Other' => 'Other',
                ]),
                SelectFilter::make('faculty_id')->label('Faculty')->relationship('faculty', 'name'),
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('image')->label('Has image')->nullable(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->deferFilters(false)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}