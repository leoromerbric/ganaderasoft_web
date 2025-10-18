<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FincasController;
use App\Http\Controllers\RebanosController;
use App\Http\Controllers\PersonalController;
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
});
