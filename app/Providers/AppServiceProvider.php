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

        // Módulo Reproductivo
        $this->app->bind(
            \App\Services\Contracts\RegistroCeloServiceInterface::class,
            \App\Services\Api\ApiRegistroCeloService::class
        );
        $this->app->bind(
            \App\Services\Contracts\ServicioAnimalServiceInterface::class,
            \App\Services\Api\ApiServicioAnimalService::class
        );
        $this->app->bind(
            \App\Services\Contracts\ReproduccionAnimalServiceInterface::class,
            \App\Services\Api\ApiReproduccionAnimalService::class
        );
        $this->app->bind(
            \App\Services\Contracts\PalpacionServiceInterface::class,
            \App\Services\Api\ApiPalpacionService::class
        );
        $this->app->bind(
            \App\Services\Contracts\SemenToroServiceInterface::class,
            \App\Services\Api\ApiSemenToroService::class
        );

        // Módulo Sanitario
        $this->app->bind(
            \App\Services\Contracts\DiagnosticoServiceInterface::class,
            \App\Services\Api\ApiDiagnosticoService::class
        );
        $this->app->bind(
            \App\Services\Contracts\TratamientoServiceInterface::class,
            \App\Services\Api\ApiTratamientoService::class
        );
        $this->app->bind(
            \App\Services\Contracts\VacunaServiceInterface::class,
            \App\Services\Api\ApiVacunaService::class
        );
        $this->app->bind(
            \App\Services\Contracts\DosisServiceInterface::class,
            \App\Services\Api\ApiDosisService::class
        );
        $this->app->bind(
            \App\Services\Contracts\CasaComercialServiceInterface::class,
            \App\Services\Api\ApiCasaComercialService::class
        );
        $this->app->bind(
            \App\Services\Contracts\HistoricoAplicacionServiceInterface::class,
            \App\Services\Api\ApiHistoricoAplicacionService::class
        );

        // Movimiento de Rebaño
        $this->app->bind(
            \App\Services\Contracts\MovimientoRebanoServiceInterface::class,
            \App\Services\Api\ApiMovimientoRebanoService::class
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
