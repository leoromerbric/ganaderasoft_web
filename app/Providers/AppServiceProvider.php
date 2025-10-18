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
        
        // For now, using Mock services for demonstration as backend is not accessible
        // To use real API: change Mock to Api namespace
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Mock\MockAuthService::class  // Change to Api\ApiAuthService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\DashboardServiceInterface::class,
            \App\Services\Mock\MockDashboardService::class
        );

        $this->app->bind(
            \App\Services\Contracts\FincasServiceInterface::class,
            \App\Services\Mock\MockFincasService::class  // Change to Api\ApiFincasService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\RebanosServiceInterface::class,
            \App\Services\Mock\MockRebanosService::class  // Change to Api\ApiRebanosService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\PersonalServiceInterface::class,
            \App\Services\Mock\MockPersonalService::class  // Change to Api\ApiPersonalService when backend is ready
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
