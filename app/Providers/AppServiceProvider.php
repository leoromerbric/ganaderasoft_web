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
        // Production configuration - using API services for all functionality
        // All services are configured to use live API endpoints
        
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            \App\Services\Api\ApiAuthService::class
        );

        $this->app->bind(
            \App\Services\Contracts\DashboardServiceInterface::class,
            \App\Services\Api\ApiDashboardService::class
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

        $this->app->bind(
            \App\Services\Contracts\ConfiguracionServiceInterface::class,
            \App\Services\Api\ApiConfiguracionService::class
        );

        $this->app->bind(
            \App\Services\Contracts\AnimalesServiceInterface::class,
            \App\Services\Api\ApiAnimalesService::class
        );

        $this->app->bind(
            \App\Services\Contracts\LactanciaServiceInterface::class,
            \App\Services\Api\ApiLactanciaService::class
        );

        $this->app->bind(
            \App\Services\Contracts\LecheServiceInterface::class,
            \App\Services\Api\ApiLecheService::class
        );

        $this->app->bind(
            \App\Services\Contracts\PesoCorporalServiceInterface::class,
            \App\Services\Api\ApiPesoCorporalService::class
        );

        $this->app->bind(
            \App\Services\Contracts\MedidasCorporalesServiceInterface::class,
            \App\Services\Api\ApiMedidasCorporalesService::class
        );

        $this->app->bind(
            \App\Services\Contracts\PersonalFincaServiceInterface::class,
            \App\Services\Api\ApiPersonalFincaService::class
        );

        $this->app->bind(
            \App\Services\Contracts\CambiosAnimalServiceInterface::class,
            \App\Services\Api\ApiCambiosAnimalService::class
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
