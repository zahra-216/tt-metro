<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Programme;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CampusStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('New applications', Application::where('status', 'new')->count())
                ->description('Waiting for a reply')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('warning')
                ->url(route('filament.admin.resources.applications.index')),
            Stat::make('Unread messages', ContactMessage::where('is_read', false)->count())
                ->description('From the contact form')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->url(route('filament.admin.resources.contact-messages.index')),
            Stat::make('Programmes', Programme::where('is_active', true)->count())
                ->description('Live on the website')
                ->icon('heroicon-o-academic-cap')
                ->url(route('filament.admin.resources.programmes.index')),
            Stat::make('Events', Event::where('is_active', true)->count())
                ->description('Published')
                ->icon('heroicon-o-calendar-days')
                ->url(route('filament.admin.resources.events.index')),
        ];
    }
}