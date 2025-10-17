<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind mock services
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Mock\MockAuthService::class
        );

        $this->app->bind(
            \App\Services\Contracts\DashboardServiceInterface::class,
            \App\Services\Mock\MockDashboardService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
