<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FincasController;
use App\Http\Controllers\RebanosController;
use App\Http\Controllers\PersonalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth API routes
Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');
Route::post('/logout', [AuthController::class, 'apiLogout'])->middleware('mock.auth')->name('api.logout');

// Protected API routes
Route::middleware(['mock.auth'])->group(function () {
    // Fincas API
    Route::get('/fincas', [FincasController::class, 'apiFincas'])->name('api.fincas');
    
    // Rebaños API
    Route::get('/rebanos', [RebanosController::class, 'apiRebanos'])->name('api.rebanos');
    
    // Personal API
    Route::get('/personal', [PersonalController::class, 'apiPersonal'])->name('api.personal');
});
