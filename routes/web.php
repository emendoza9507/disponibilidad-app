<?php

use App\Http\Controllers\AutoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\NeumaticoController;
use App\Http\Controllers\Reportes\RBateriaController;
use App\Http\Controllers\Reportes\RNeumaticoController;
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
    });

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

    Route::resource('departamentos', DepartamentoController:: class)->only('index');
    Route::resource('autos', AutoController:: class)->only('index');
    Route::resource('neumatico', NeumaticoController:: class)->only('index');
    Route::resource('reportes', ReportesController::class)->only('index');

    Route::name('reporte.')->group(function () {
        Route::resource('reporte/bateria',RBateriaController::class)->only('index');
        Route::resource('reporte/neumatico',RNeumaticoController::class)->only('index');
    });
});
