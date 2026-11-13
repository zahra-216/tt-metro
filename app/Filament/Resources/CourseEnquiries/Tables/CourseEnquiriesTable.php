<?php

namespace App\Filament\Resources\CourseEnquiries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CourseEnquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Received')->dateTime('d M Y, g:i A')->sortable(),
                TextColumn::make('programme_title')->label('Programme')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->label('WhatsApp')->searchable(),
                TextColumn::make('message')->limit(50)->tooltip(fn ($record) => $record->message),
                IconColumn::make('is_read')->label('Read')->boolean(),
            ])
            ->recordActions([EditAction::make()->label('Open'), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
