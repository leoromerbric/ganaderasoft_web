<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FincasController;
use App\Http\Controllers\RebanosController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PersonalFincaController;
use App\Http\Controllers\CambiosAnimalController;
use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\LactanciaController;
use App\Http\Controllers\LecheController;
use App\Http\Controllers\PesoCorporalController;
use App\Http\Controllers\MedidasCorporalesController;
use App\Http\Controllers\RegistroCeloController;
use App\Http\Controllers\ServicioAnimalController;
use App\Http\Controllers\ReproduccionAnimalController;
use App\Http\Controllers\PalpacionController;
use App\Http\Controllers\SemenToroController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\VacunaController;
use App\Http\Controllers\DosisController;
use App\Http\Controllers\CasaComercialController;
use App\Http\Controllers\HistoricoAplicacionController;
use App\Http\Controllers\MovimientoRebanoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['mock.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Fincas routes
    Route::get('/fincas', [FincasController::class, 'index'])->name('fincas.index');
    Route::get('/fincas/create', [FincasController::class, 'create'])->name('fincas.create');
    Route::post('/fincas', [FincasController::class, 'store'])->name('fincas.store');
    Route::get('/fincas/{id}/edit', [FincasController::class, 'edit'])->name('fincas.edit');
    Route::put('/fincas/{id}', [FincasController::class, 'update'])->name('fincas.update');
    Route::get('/fincas/{id}/dashboard', [FincasController::class, 'dashboard'])->name('fincas.dashboard');
    
    // Rebaños routes
    Route::get('/rebanos', [RebanosController::class, 'index'])->name('rebanos.index');
    Route::get('/rebanos/create', [RebanosController::class, 'create'])->name('rebanos.create');
    Route::post('/rebanos', [RebanosController::class, 'store'])->name('rebanos.store');
    Route::get('/rebanos/{id}/edit', [RebanosController::class, 'edit'])->name('rebanos.edit');
    Route::put('/rebanos/{id}', [RebanosController::class, 'update'])->name('rebanos.update');
    
    // Personal routes
    Route::get('/personal', [PersonalController::class, 'index'])->name('personal.index');
    Route::get('/personal/create', [PersonalController::class, 'create'])->name('personal.create');
    Route::post('/personal', [PersonalController::class, 'store'])->name('personal.store');
    Route::get('/personal/{id}/edit', [PersonalController::class, 'edit'])->name('personal.edit');
    Route::put('/personal/{id}', [PersonalController::class, 'update'])->name('personal.update');
    
    // Animales routes
    Route::get('/animales', [AnimalesController::class, 'index'])->name('animales.index');
    Route::get('/animales/create', [AnimalesController::class, 'create'])->name('animales.create');
    Route::post('/animales', [AnimalesController::class, 'store'])->name('animales.store');
    Route::get('/animales/{id}', [AnimalesController::class, 'show'])->name('animales.show');
    Route::get('/animales/{id}/edit', [AnimalesController::class, 'edit'])->name('animales.edit');
    Route::put('/animales/{id}', [AnimalesController::class, 'update'])->name('animales.update');
    
    // Lactancia routes - Gestión de períodos de lactancia
    Route::get('/lactancia', [LactanciaController::class, 'index'])->name('lactancia.index');
    Route::get('/lactancia/create', [LactanciaController::class, 'create'])->name('lactancia.create');
    Route::post('/lactancia', [LactanciaController::class, 'store'])->name('lactancia.store');
    Route::get('/lactancia/{id}', [LactanciaController::class, 'show'])->name('lactancia.show');
    Route::get('/lactancia/{id}/edit', [LactanciaController::class, 'edit'])->name('lactancia.edit');
    Route::put('/lactancia/{id}', [LactanciaController::class, 'update'])->name('lactancia.update');
    Route::delete('/lactancia/{id}', [LactanciaController::class, 'destroy'])->name('lactancia.destroy');
    Route::get('/lactancia/animal/{id}/etapa', [LactanciaController::class, 'getAnimalEtapa'])->name('lactancia.animal.etapa');
    
    // Leche routes - Registros de producción lechera
    Route::get('/leche', [LecheController::class, 'index'])->name('leche.index');
    Route::get('/leche/create', [LecheController::class, 'create'])->name('leche.create');
    Route::post('/leche', [LecheController::class, 'store'])->name('leche.store');
    Route::get('/leche/{id}', [LecheController::class, 'show'])->name('leche.show');
    Route::get('/leche/{id}/edit', [LecheController::class, 'edit'])->name('leche.edit');
    Route::put('/leche/{id}', [LecheController::class, 'update'])->name('leche.update');
    Route::delete('/leche/{id}', [LecheController::class, 'destroy'])->name('leche.destroy');
    
    // Peso Corporal routes - Registros de peso de animales
    Route::get('/peso-corporal', [PesoCorporalController::class, 'index'])->name('peso-corporal.index');
    Route::get('/peso-corporal/create', [PesoCorporalController::class, 'create'])->name('peso-corporal.create');
    Route::post('/peso-corporal', [PesoCorporalController::class, 'store'])->name('peso-corporal.store');
    Route::get('/peso-corporal/{id}', [PesoCorporalController::class, 'show'])->name('peso-corporal.show');
    Route::get('/peso-corporal/{id}/edit', [PesoCorporalController::class, 'edit'])->name('peso-corporal.edit');
    Route::put('/peso-corporal/{id}', [PesoCorporalController::class, 'update'])->name('peso-corporal.update');
    Route::delete('/peso-corporal/{id}', [PesoCorporalController::class, 'destroy'])->name('peso-corporal.destroy');
    
    // Medidas Corporales routes - Registros de medidas corporales de animales
    Route::get('/medidas-corporales', [MedidasCorporalesController::class, 'index'])->name('medidas-corporales.index');
    Route::get('/medidas-corporales/create', [MedidasCorporalesController::class, 'create'])->name('medidas-corporales.create');
    Route::post('/medidas-corporales', [MedidasCorporalesController::class, 'store'])->name('medidas-corporales.store');
    Route::get('/medidas-corporales/{id}', [MedidasCorporalesController::class, 'show'])->name('medidas-corporales.show');
    Route::get('/medidas-corporales/{id}/edit', [MedidasCorporalesController::class, 'edit'])->name('medidas-corporales.edit');
    Route::put('/medidas-corporales/{id}', [MedidasCorporalesController::class, 'update'])->name('medidas-corporales.update');
    Route::delete('/medidas-corporales/{id}', [MedidasCorporalesController::class, 'destroy'])->name('medidas-corporales.destroy');
    
    // Personal de Finca routes - Gestión de personal de las fincas
    Route::get('/personal-finca', [PersonalFincaController::class, 'index'])->name('personal-finca.index');
    Route::get('/personal-finca/create', [PersonalFincaController::class, 'create'])->name('personal-finca.create');
    Route::post('/personal-finca', [PersonalFincaController::class, 'store'])->name('personal-finca.store');
    Route::get('/personal-finca/{id}', [PersonalFincaController::class, 'show'])->name('personal-finca.show');
    Route::get('/personal-finca/{id}/edit', [PersonalFincaController::class, 'edit'])->name('personal-finca.edit');
    Route::put('/personal-finca/{id}', [PersonalFincaController::class, 'update'])->name('personal-finca.update');
    Route::delete('/personal-finca/{id}', [PersonalFincaController::class, 'destroy'])->name('personal-finca.destroy');
    
    // Cambios de Animal routes - Historial de cambios y desarrollo
    Route::get('/cambios-animal', [CambiosAnimalController::class, 'index'])->name('cambios-animal.index');
    Route::get('/cambios-animal/create', [CambiosAnimalController::class, 'create'])->name('cambios-animal.create');
    Route::post('/cambios-animal', [CambiosAnimalController::class, 'store'])->name('cambios-animal.store');
    Route::get('/cambios-animal/{id}', [CambiosAnimalController::class, 'show'])->name('cambios-animal.show');
    Route::delete('/cambios-animal/{id}', [CambiosAnimalController::class, 'destroy'])->name('cambios-animal.destroy');
    // AJAX route for getting animal stage
    Route::get('/cambios-animal/animal/{id}/etapa', [CambiosAnimalController::class, 'getAnimalEtapa'])->name('cambios-animal.animal.etapa');

    // ===================== MÓDULO REPRODUCTIVO =====================
    // Registro de Celo
    Route::get('/registro-celo', [RegistroCeloController::class, 'index'])->name('registro-celo.index');
    Route::get('/registro-celo/create', [RegistroCeloController::class, 'create'])->name('registro-celo.create');
    Route::post('/registro-celo', [RegistroCeloController::class, 'store'])->name('registro-celo.store');
    Route::get('/registro-celo/{id}', [RegistroCeloController::class, 'show'])->name('registro-celo.show');
    Route::get('/registro-celo/{id}/edit', [RegistroCeloController::class, 'edit'])->name('registro-celo.edit');
    Route::put('/registro-celo/{id}', [RegistroCeloController::class, 'update'])->name('registro-celo.update');
    Route::delete('/registro-celo/{id}', [RegistroCeloController::class, 'destroy'])->name('registro-celo.destroy');

    // Servicio Animal
    Route::get('/servicio-animal', [ServicioAnimalController::class, 'index'])->name('servicio-animal.index');
    Route::get('/servicio-animal/create', [ServicioAnimalController::class, 'create'])->name('servicio-animal.create');
    Route::post('/servicio-animal', [ServicioAnimalController::class, 'store'])->name('servicio-animal.store');
    Route::get('/servicio-animal/{id}', [ServicioAnimalController::class, 'show'])->name('servicio-animal.show');
    Route::get('/servicio-animal/{id}/edit', [ServicioAnimalController::class, 'edit'])->name('servicio-animal.edit');
    Route::put('/servicio-animal/{id}', [ServicioAnimalController::class, 'update'])->name('servicio-animal.update');
    Route::delete('/servicio-animal/{id}', [ServicioAnimalController::class, 'destroy'])->name('servicio-animal.destroy');

    // Reproducción Animal
    Route::get('/reproduccion-animal', [ReproduccionAnimalController::class, 'index'])->name('reproduccion-animal.index');
    Route::get('/reproduccion-animal/create', [ReproduccionAnimalController::class, 'create'])->name('reproduccion-animal.create');
    Route::post('/reproduccion-animal', [ReproduccionAnimalController::class, 'store'])->name('reproduccion-animal.store');
    Route::get('/reproduccion-animal/{id}', [ReproduccionAnimalController::class, 'show'])->name('reproduccion-animal.show');
    Route::get('/reproduccion-animal/{id}/edit', [ReproduccionAnimalController::class, 'edit'])->name('reproduccion-animal.edit');
    Route::put('/reproduccion-animal/{id}', [ReproduccionAnimalController::class, 'update'])->name('reproduccion-animal.update');
    Route::delete('/reproduccion-animal/{id}', [ReproduccionAnimalController::class, 'destroy'])->name('reproduccion-animal.destroy');

    // Palpación
    Route::get('/palpacion', [PalpacionController::class, 'index'])->name('palpacion.index');
    Route::get('/palpacion/create', [PalpacionController::class, 'create'])->name('palpacion.create');
    Route::post('/palpacion', [PalpacionController::class, 'store'])->name('palpacion.store');
    Route::get('/palpacion/{id}', [PalpacionController::class, 'show'])->name('palpacion.show');
    Route::get('/palpacion/{id}/edit', [PalpacionController::class, 'edit'])->name('palpacion.edit');
    Route::put('/palpacion/{id}', [PalpacionController::class, 'update'])->name('palpacion.update');
    Route::delete('/palpacion/{id}', [PalpacionController::class, 'destroy'])->name('palpacion.destroy');

    // Semen de Toro
    Route::get('/semen-toro', [SemenToroController::class, 'index'])->name('semen-toro.index');
    Route::get('/semen-toro/create', [SemenToroController::class, 'create'])->name('semen-toro.create');
    Route::post('/semen-toro', [SemenToroController::class, 'store'])->name('semen-toro.store');
    Route::get('/semen-toro/{id}', [SemenToroController::class, 'show'])->name('semen-toro.show');
    Route::get('/semen-toro/{id}/edit', [SemenToroController::class, 'edit'])->name('semen-toro.edit');
    Route::put('/semen-toro/{id}', [SemenToroController::class, 'update'])->name('semen-toro.update');
    Route::delete('/semen-toro/{id}', [SemenToroController::class, 'destroy'])->name('semen-toro.destroy');

    // ===================== MÓDULO SANITARIO =====================
    // Diagnóstico
    Route::get('/diagnostico', [DiagnosticoController::class, 'index'])->name('diagnostico.index');
    Route::get('/diagnostico/create', [DiagnosticoController::class, 'create'])->name('diagnostico.create');
    Route::post('/diagnostico', [DiagnosticoController::class, 'store'])->name('diagnostico.store');
    Route::get('/diagnostico/{id}', [DiagnosticoController::class, 'show'])->name('diagnostico.show');
    Route::get('/diagnostico/{id}/edit', [DiagnosticoController::class, 'edit'])->name('diagnostico.edit');
    Route::put('/diagnostico/{id}', [DiagnosticoController::class, 'update'])->name('diagnostico.update');
    Route::delete('/diagnostico/{id}', [DiagnosticoController::class, 'destroy'])->name('diagnostico.destroy');

    // Tratamiento
    Route::get('/tratamiento', [TratamientoController::class, 'index'])->name('tratamiento.index');
    Route::get('/tratamiento/create', [TratamientoController::class, 'create'])->name('tratamiento.create');
    Route::post('/tratamiento', [TratamientoController::class, 'store'])->name('tratamiento.store');
    Route::get('/tratamiento/{id}', [TratamientoController::class, 'show'])->name('tratamiento.show');
    Route::get('/tratamiento/{id}/edit', [TratamientoController::class, 'edit'])->name('tratamiento.edit');
    Route::put('/tratamiento/{id}', [TratamientoController::class, 'update'])->name('tratamiento.update');
    Route::delete('/tratamiento/{id}', [TratamientoController::class, 'destroy'])->name('tratamiento.destroy');

    // Vacunas
    Route::get('/vacunas', [VacunaController::class, 'index'])->name('vacuna.index');
    Route::get('/vacunas/create', [VacunaController::class, 'create'])->name('vacuna.create');
    Route::post('/vacunas', [VacunaController::class, 'store'])->name('vacuna.store');
    Route::get('/vacunas/{id}', [VacunaController::class, 'show'])->name('vacuna.show');
    Route::get('/vacunas/{id}/edit', [VacunaController::class, 'edit'])->name('vacuna.edit');
    Route::put('/vacunas/{id}', [VacunaController::class, 'update'])->name('vacuna.update');
    Route::delete('/vacunas/{id}', [VacunaController::class, 'destroy'])->name('vacuna.destroy');

    // Dosis
    Route::get('/dosis', [DosisController::class, 'index'])->name('dosis.index');
    Route::get('/dosis/create', [DosisController::class, 'create'])->name('dosis.create');
    Route::post('/dosis', [DosisController::class, 'store'])->name('dosis.store');
    Route::get('/dosis/{id}', [DosisController::class, 'show'])->name('dosis.show');
    Route::get('/dosis/{id}/edit', [DosisController::class, 'edit'])->name('dosis.edit');
    Route::put('/dosis/{id}', [DosisController::class, 'update'])->name('dosis.update');
    Route::delete('/dosis/{id}', [DosisController::class, 'destroy'])->name('dosis.destroy');

    // Casas Comerciales
    Route::get('/casas-comerciales', [CasaComercialController::class, 'index'])->name('casa-comercial.index');
    Route::get('/casas-comerciales/create', [CasaComercialController::class, 'create'])->name('casa-comercial.create');
    Route::post('/casas-comerciales', [CasaComercialController::class, 'store'])->name('casa-comercial.store');
    Route::get('/casas-comerciales/{id}', [CasaComercialController::class, 'show'])->name('casa-comercial.show');
    Route::get('/casas-comerciales/{id}/edit', [CasaComercialController::class, 'edit'])->name('casa-comercial.edit');
    Route::put('/casas-comerciales/{id}', [CasaComercialController::class, 'update'])->name('casa-comercial.update');
    Route::delete('/casas-comerciales/{id}', [CasaComercialController::class, 'destroy'])->name('casa-comercial.destroy');

    // Histórico de Aplicación
    Route::get('/historico-aplicacion', [HistoricoAplicacionController::class, 'index'])->name('historico-aplicacion.index');
    Route::get('/historico-aplicacion/create', [HistoricoAplicacionController::class, 'create'])->name('historico-aplicacion.create');
    Route::post('/historico-aplicacion', [HistoricoAplicacionController::class, 'store'])->name('historico-aplicacion.store');
    Route::get('/historico-aplicacion/{id}', [HistoricoAplicacionController::class, 'show'])->name('historico-aplicacion.show');
    Route::get('/historico-aplicacion/{id}/edit', [HistoricoAplicacionController::class, 'edit'])->name('historico-aplicacion.edit');
    Route::put('/historico-aplicacion/{id}', [HistoricoAplicacionController::class, 'update'])->name('historico-aplicacion.update');
    Route::delete('/historico-aplicacion/{id}', [HistoricoAplicacionController::class, 'destroy'])->name('historico-aplicacion.destroy');

    // ===================== MOVIMIENTOS DE REBAÑO =====================
    Route::get('/movimiento-rebano', [MovimientoRebanoController::class, 'index'])->name('movimiento-rebano.index');
    Route::get('/movimiento-rebano/create', [MovimientoRebanoController::class, 'create'])->name('movimiento-rebano.create');
    Route::post('/movimiento-rebano', [MovimientoRebanoController::class, 'store'])->name('movimiento-rebano.store');
    Route::get('/movimiento-rebano/{id}', [MovimientoRebanoController::class, 'show'])->name('movimiento-rebano.show');
    Route::get('/movimiento-rebano/{id}/edit', [MovimientoRebanoController::class, 'edit'])->name('movimiento-rebano.edit');
    Route::put('/movimiento-rebano/{id}', [MovimientoRebanoController::class, 'update'])->name('movimiento-rebano.update');
    Route::delete('/movimiento-rebano/{id}', [MovimientoRebanoController::class, 'destroy'])->name('movimiento-rebano.destroy');
});
