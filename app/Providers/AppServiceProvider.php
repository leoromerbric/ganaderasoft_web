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
        // Bind API services (replace Mock with Api namespace to use real backend)
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
