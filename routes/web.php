<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\Consecutivos\CBateriaController;
use App\Http\Controllers\Consecutivos\CNeumaticoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\NeumaticoController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\Reportes\RBateriaController;
use App\Http\Controllers\Reportes\RMantenimientosController;
use App\Http\Controllers\Reportes\RNeumaticoController;
use App\Http\Controllers\Reportes\ROrdenesEstadoController;
use App\Http\Controllers\Reportes\RProduccionProceso;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnectionController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        return redirect('/dashboard');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(ConnectionController::class)->prefix('connections')->group(function () {
        Route::get('/', 'index')->name('connections.index');
        Route::get('/{name}', 'show')->name('connections.show');
        Route::post('/', 'store')->name('new_connection');
    });


    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index')->name('users.index');
    });


    Route::middleware([
        \App\Http\Middleware\EnsureConnectionExists::class
    ])->group(function (){
        Route::resource('departamentos', DepartamentoController:: class)->only('index');

        Route::name('auto.show.ordenes')->get('autos/{auto}/ordenes', [AutoController::class, 'jsonShow']);
        Route::resource('autos', AutoController:: class)->only(['index', 'show']);

        Route::name('reporte')->resource('reporte/bateria',RBateriaController::class)->only('index');
        Route::name('reporte')->resource('reporte/neumatico',RNeumaticoController::class)->only('index');
        Route::name('reporte')->resource('reporte/produccion/proceso', RProduccionProceso::class)->only('index');
        Route::name('reporte')->resource('reporte/ordenes', ROrdenesEstadoController::class)->only('index');
        Route::name('reporte')->resource('reporte/mantenimiento', RMantenimientosController::class)->only('index');

        Route::resource('orden', OrdenTrabajoController::class)->only(['index', 'show']);

        Route::name('consecutivo')->resource('consecutivo/bateria', CBateriaController::class)->only(['index', 'store']);
        Route::name('consecutivo')->resource('consecutivo/neumatico', CNeumaticoController::class)->only(['index', 'store']);

        Route::name('consecutivo.')->group(function (){
           Route::controller(CNeumaticoController::class)->prefix('consecutivo/neumatico')->group(function () {
               Route::name('neumatico.all')->get('/all', 'all');
               Route::name('neumatico.show')->get('/{neumatico}', 'show');
           });

            Route::controller(CBateriaController::class)->prefix('consecutivo/bateria')->group(function () {
                Route::name('bateria.all')->get('/all', 'all');
                Route::name('bateria.show')->get('/{bateria}', 'show');
            });
        });
    });
});
