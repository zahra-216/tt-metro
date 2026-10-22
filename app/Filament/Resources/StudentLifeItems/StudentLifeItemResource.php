<?php

namespace App\Filament\Resources\StudentLifeItems;

use App\Filament\Resources\StudentLifeItems\Pages\CreateStudentLifeItem;
use App\Filament\Resources\StudentLifeItems\Pages\EditStudentLifeItem;
use App\Filament\Resources\StudentLifeItems\Pages\ListStudentLifeItems;
use App\Filament\Resources\StudentLifeItems\Schemas\StudentLifeItemForm;
use App\Filament\Resources\StudentLifeItems\Tables\StudentLifeItemsTable;
use App\Models\StudentLifeItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentLifeItemResource extends Resource
{
    protected static ?string $model = StudentLifeItem::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Site';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StudentLifeItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentLifeItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentLifeItems::route('/'),
            'create' => CreateStudentLifeItem::route('/create'),
            'edit' => EditStudentLifeItem::route('/{record}/edit'),
        ];
    }
}
