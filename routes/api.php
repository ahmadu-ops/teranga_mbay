<?php

use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('stats')->group(function () {
    Route::get('/kpis', [StatsController::class, 'kpis']);
    Route::get('/graphique-recoltes', [StatsController::class, 'graphiqueRecoltes']);
    Route::get('/graphique-ventes', [StatsController::class, 'graphiqueVentes']);
    Route::get('/graphique-pertes', [StatsController::class, 'graphiquePertes']);
    Route::get('/repartition-stock', [StatsController::class, 'repartitionStock']);
    Route::get('/ventes', [StatsController::class, 'statsVentes']);
    Route::get('/recoltes', [StatsController::class, 'statsRecoltes']);
    Route::get('/pertes', [StatsController::class, 'statsPertes']);
    Route::get('/pertes-par-motif', [StatsController::class, 'statspertesParMotif']);
});