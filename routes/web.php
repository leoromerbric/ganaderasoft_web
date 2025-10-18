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
    
    // Rebaños routes
    Route::get('/rebanos', [RebanosController::class, 'index'])->name('rebanos.index');
    
    // Personal routes
    Route::get('/personal', [PersonalController::class, 'index'])->name('personal.index');
});
