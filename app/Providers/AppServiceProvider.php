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
        // Bind services - switch between Mock and Api implementations
        // Use Api\* classes when backend is accessible, Mock\* for demonstration
        
        // Using API services for Auth, Fincas, Rebanos, and Personal
        // Using Mock service for Dashboard (not required by the issue)
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Api\ApiAuthService::class
        );

        $this->app->bind(
            \App\Services\Contracts\DashboardServiceInterface::class,
            \App\Services\Mock\MockDashboardService::class
        );

        $this->app->bind(
            \App\Services\Contracts\FincasServiceInterface::class,
            \App\Services\Api\ApiFincasService::class
        );

        $this->app->bind(
            \App\Services\Contracts\RebanosServiceInterface::class,
            \App\Services\Api\ApiRebanosService::class
        );

        $this->app->bind(
            \App\Services\Contracts\PersonalServiceInterface::class,
            \App\Services\Api\ApiPersonalService::class
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
