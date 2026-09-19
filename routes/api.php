<?php

use App\Http\Controllers\PesoCorporalController;
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

// Protected API routes
Route::middleware(['mock.auth'])->group(function () {
    // Peso Corporal API
    Route::get('/peso-corporal/animal/{id}/etapa', [PesoCorporalController::class, 'getAnimalEtapa'])->name('api.peso-corporal.animal.etapa');
});
