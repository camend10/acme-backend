<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Conductor\ConductorController;
use App\Http\Controllers\Generales\GeneralesController;
use App\Http\Controllers\Informe\InformeController;
use App\Http\Controllers\Propietario\PropietarioController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Vehiculo\VehiculoController;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
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

Route::group([
    // 'middleware' => 'auth:api',
    'prefix' => 'auth',
], function ($router) {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/verificar', [AuthController::class, 'verificar'])->name('verificar');
});

Route::get('/informes/pdf', [InformeController::class, 'pdf']);

// Protected routes
Route::middleware(['jwt.verify'])->group(function () {

    Route::group([
        'prefix' => 'configuracion',
    ], function ($router) {

        Route::patch('/users/{id}/cambiar-estado', [UserController::class, 'estado']);
        Route::post('/users/{id}', [UserController::class, 'update']);
        Route::resource("users", UserController::class);

        Route::post('/propietarios/{id}', [PropietarioController::class, 'update']);
        Route::resource("propietarios", PropietarioController::class);

        Route::post('/conductores/{id}', [ConductorController::class, 'update']);
        Route::resource("conductores", ConductorController::class);

        Route::post('/vehiculos/{id}', [VehiculoController::class, 'update']);
        Route::resource("vehiculos", VehiculoController::class);

        Route::post('/informes/index', [InformeController::class, 'index']);        
        Route::resource("informes", InformeController::class);
    });    

    Route::group([
        'prefix' => 'generales',
    ], function ($router) {
        Route::post('/configuraciones', [GeneralesController::class, 'configuraciones']);
    });
});
