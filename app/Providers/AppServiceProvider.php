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
        
        // API services are ready for Auth, Fincas, Rebanos, and Personal
        // To enable APIs, change Mock to Api namespace below
        // For demonstration, using Mock services (backend API may not be accessible)
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Api\ApiAuthService::class  // Change to Api\ApiAuthService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\DashboardServiceInterface::class,
            \App\Services\Mock\MockDashboardService::class
        );

        $this->app->bind(
            \App\Services\Contracts\FincasServiceInterface::class,
            \App\Services\Api\ApiFincasService::class  // Change to Api\ApiFincasService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\RebanosServiceInterface::class,
            \App\Services\Api\ApiRebanosService::class  // Change to Api\ApiRebanosService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\PersonalServiceInterface::class,
            \App\Services\Api\ApiPersonalService::class  // Change to Api\ApiPersonalService when backend is ready
        );

        $this->app->bind(
            \App\Services\Contracts\ConfiguracionServiceInterface::class,
            \App\Services\Api\ApiConfiguracionService::class
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
