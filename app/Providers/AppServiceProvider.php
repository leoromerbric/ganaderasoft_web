<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

// Contratos (Interfaces)
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\FincasServiceInterface;
use App\Services\Contracts\RebanosServiceInterface;
use App\Services\Contracts\PersonalServiceInterface;
use App\Services\Contracts\ConfiguracionServiceInterface;
use App\Services\Contracts\AnimalesServiceInterface;
use App\Services\Contracts\LactanciaServiceInterface;
use App\Services\Contracts\LecheServiceInterface;
use App\Services\Contracts\PesoCorporalServiceInterface;
use App\Services\Contracts\MedidasCorporalesServiceInterface;
use App\Services\Contracts\PersonalFincaServiceInterface;
use App\Services\Contracts\CambiosAnimalServiceInterface;
use App\Services\Contracts\RegistroCeloServiceInterface;
use App\Services\Contracts\ServicioAnimalServiceInterface;
use App\Services\Contracts\ReproduccionAnimalServiceInterface;
use App\Services\Contracts\PalpacionServiceInterface;
use App\Services\Contracts\SemenToroServiceInterface;
use App\Services\Contracts\DiagnosticoServiceInterface;
use App\Services\Contracts\TratamientoServiceInterface;
use App\Services\Contracts\VacunaServiceInterface;
use App\Services\Contracts\VacunacionServiceInterface;
use App\Services\Contracts\CasaComercialServiceInterface;
use App\Services\Contracts\MovimientoRebanoServiceInterface;
use App\Services\Contracts\ArbolGenServiceInterface;
use App\Services\Contracts\ReportesServiceInterface;
use App\Services\Contracts\AdminServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\TipoTrabajadorServiceInterface;
use App\Services\Contracts\TipoAnimalServiceInterface;
use App\Services\Contracts\ComposicionRazaServiceInterface;
use App\Services\Contracts\EtapaServiceInterface;
use App\Services\Contracts\EstadoSaludServiceInterface;
use App\Services\Contracts\DiaPalpacionServiceInterface;
use App\Services\Contracts\FoliculoServiceInterface;

// Implementaciones API
use App\Services\Api\ApiAuthService;
use App\Services\Api\ApiDashboardService;
use App\Services\Api\ApiFincasService;
use App\Services\Api\ApiRebanosService;
use App\Services\Api\ApiPersonalService;
use App\Services\Api\ApiConfiguracionService;
use App\Services\Api\ApiAnimalesService;
use App\Services\Api\ApiLactanciaService;
use App\Services\Api\ApiLecheService;
use App\Services\Api\ApiPesoCorporalService;
use App\Services\Api\ApiMedidasCorporalesService;
use App\Services\Api\ApiPersonalFincaService;
use App\Services\Api\ApiCambiosAnimalService;
use App\Services\Api\ApiRegistroCeloService;
use App\Services\Api\ApiServicioAnimalService;
use App\Services\Api\ApiReproduccionAnimalService;
use App\Services\Api\ApiPalpacionService;
use App\Services\Api\ApiSemenToroService;
use App\Services\Api\ApiDiagnosticoService;
use App\Services\Api\ApiTratamientoService;
use App\Services\Api\ApiVacunaService;
use App\Services\Api\ApiVacunacionService;
use App\Services\Api\ApiCasaComercialService;
use App\Services\Api\ApiMovimientoRebanoService;
use App\Services\Api\ApiArbolGenService;
use App\Services\Api\ApiReportesService;
use App\Services\Api\ApiAdminService;
use App\Services\Api\ApiUserService;
use App\Services\Api\ApiTipoTrabajadorService;
use App\Services\Api\ApiTipoAnimalService;
use App\Services\Api\ApiComposicionRazaService;
use App\Services\Api\ApiEtapaService;
use App\Services\Api\ApiEstadoSaludService;
use App\Services\Api\ApiDiaPalpacionService;
use App\Services\Api\ApiFoliculoService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, ApiAuthService::class);
        $this->app->bind(DashboardServiceInterface::class, ApiDashboardService::class);
        $this->app->bind(FincasServiceInterface::class, ApiFincasService::class);
        $this->app->bind(RebanosServiceInterface::class, ApiRebanosService::class);
        $this->app->bind(PersonalServiceInterface::class, ApiPersonalService::class);
        $this->app->bind(ConfiguracionServiceInterface::class, ApiConfiguracionService::class);
        $this->app->bind(AnimalesServiceInterface::class, ApiAnimalesService::class);
        $this->app->bind(LactanciaServiceInterface::class, ApiLactanciaService::class);
        $this->app->bind(LecheServiceInterface::class, ApiLecheService::class);
        $this->app->bind(PesoCorporalServiceInterface::class, ApiPesoCorporalService::class);
        $this->app->bind(MedidasCorporalesServiceInterface::class, ApiMedidasCorporalesService::class);
        $this->app->bind(PersonalFincaServiceInterface::class, ApiPersonalFincaService::class);
        $this->app->bind(CambiosAnimalServiceInterface::class, ApiCambiosAnimalService::class);

        // Módulo Reproductivo
        $this->app->bind(RegistroCeloServiceInterface::class, ApiRegistroCeloService::class);
        $this->app->bind(ServicioAnimalServiceInterface::class, ApiServicioAnimalService::class);
        $this->app->bind(ReproduccionAnimalServiceInterface::class, ApiReproduccionAnimalService::class);
        $this->app->bind(PalpacionServiceInterface::class, ApiPalpacionService::class);
        $this->app->bind(SemenToroServiceInterface::class, ApiSemenToroService::class);

        // Módulo Sanitario
        $this->app->bind(DiagnosticoServiceInterface::class, ApiDiagnosticoService::class);
        $this->app->bind(TratamientoServiceInterface::class, ApiTratamientoService::class);
        $this->app->bind(VacunaServiceInterface::class, ApiVacunaService::class);
        $this->app->bind(VacunacionServiceInterface::class, ApiVacunacionService::class);
        $this->app->bind(CasaComercialServiceInterface::class, ApiCasaComercialService::class);

        // Movimiento de Rebaño
        $this->app->bind(MovimientoRebanoServiceInterface::class, ApiMovimientoRebanoService::class);

        // Árbol Genealógico
        $this->app->bind(ArbolGenServiceInterface::class, ApiArbolGenService::class);

        // Módulo de Reportes
        $this->app->bind(ReportesServiceInterface::class, ApiReportesService::class);

        // Módulo de Administración y Usuarios
        $this->app->bind(AdminServiceInterface::class, ApiAdminService::class);
        $this->app->bind(UserServiceInterface::class, ApiUserService::class);

        // Servicios para Catálogos Maestros de Administración
        $this->app->bind(TipoTrabajadorServiceInterface::class, ApiTipoTrabajadorService::class);
        $this->app->bind(TipoAnimalServiceInterface::class, ApiTipoAnimalService::class);
        $this->app->bind(ComposicionRazaServiceInterface::class, ApiComposicionRazaService::class);
        $this->app->bind(EtapaServiceInterface::class, ApiEtapaService::class);
        $this->app->bind(EstadoSaludServiceInterface::class, ApiEstadoSaludService::class);
        $this->app->bind(DiaPalpacionServiceInterface::class, ApiDiaPalpacionService::class);
        $this->app->bind(FoliculoServiceInterface::class, ApiFoliculoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
