<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestApplications extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Latest applications';

    public function table(Table $table): Table
    {
        return $table
            ->query(Application::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('full_name')->label('Name'),
                TextColumn::make('programme.title')->label('Programme')->placeholder('Not specified'),
                TextColumn::make('phone'),
                TextColumn::make('email'),
                TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'new' => 'warning', 'contacted' => 'info', default => 'gray',
                }),
                TextColumn::make('created_at')->label('Received')->since(),
            ])
            ->emptyStateHeading('No applications yet');
    }
}