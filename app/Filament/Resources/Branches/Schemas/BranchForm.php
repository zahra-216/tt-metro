<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Branch name')
                    ->placeholder('Batticaloa Branch')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('phone_1')
                    ->label('Phone')
                    ->tel(),
                TextInput::make('phone_2')
                    ->label('Second phone (optional)')
                    ->tel(),
                TextInput::make('map_url')
                    ->label('Google Maps link')
                    ->helperText('Paste the share link from Google Maps. Visitors open it with the "Get directions" button.')
                    ->url()
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Smaller numbers show first.'),
                Toggle::make('is_active')
                    ->label('Show on website')
                    ->default(true),
            ]);
    }
}