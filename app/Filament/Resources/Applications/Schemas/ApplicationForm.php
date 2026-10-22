<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('nic'),
                DatePicker::make('date_of_birth'),
                TextInput::make('highest_qualification'),
                Select::make('programme_id')
                    ->relationship('programme', 'title'),
                TextInput::make('programme_other'),
                Textarea::make('message')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['new' => 'new', 'contacted' => 'contacted', 'closed' => 'closed'])
                    ->default('new')
                    ->required(),
            ]);
    }
}
