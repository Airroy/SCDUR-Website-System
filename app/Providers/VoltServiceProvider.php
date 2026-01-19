<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Mount Volt directories - order matters for component resolution
        Volt::mount([
            resource_path('views/pages'),
            resource_path('views/livewire'),
        ]);
    }
}
