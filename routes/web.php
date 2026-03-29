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
});
