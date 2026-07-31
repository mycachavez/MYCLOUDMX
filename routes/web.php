<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticarController;
use App\Http\Controllers\CaratulaController;
use App\Http\Controllers\ReporteDomicilioController;

Route::post('/autenticar', [AutenticarController::class, 'index']);
Route::post('/logout', [AutenticarController::class, 'logout']);

Route::prefix('api')->middleware('banco.auth')->group(function () {
   
   Route::get('/banco', [AutenticarController::class, 'usuarioActual']);
   Route::get('/getCaratulas', [CaratulaController::class, 'index']);
   Route::get('/getReportesDomicilio', [ReporteDomicilioController::class, 'index']);

});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');