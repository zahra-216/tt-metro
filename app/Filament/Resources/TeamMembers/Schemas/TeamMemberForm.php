<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('position'),
                Select::make('group')
                    ->label('Section')
                    ->options(['board' => 'Board of Directors', 'council' => 'Governing Council', 'senate' => 'Academic Senate', 'staff' => 'Academic & Management Staff', 'lecturers' => 'Lecturers'])
                    ->default('board')
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('photo'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
