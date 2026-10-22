<?php

namespace App\Providers;

use App\Models\Faculty;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('navFaculties', Schema::hasTable('faculties') ? Faculty::where('is_active', true)->orderBy('sort_order')->get() : collect());
        });

        // Admin success and error messages close on their own after 3 seconds.
        Notification::configureUsing(fn (Notification $notification) => $notification->duration(3000));
    }
}
