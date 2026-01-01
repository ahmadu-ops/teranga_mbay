<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VarieteController;
use App\Http\Controllers\RecolteController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\PerteController;
use Illuminate\Support\Facades\Route;

// Page d'accueil -> Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Produits
Route::resource('produits', ProduitController::class);

// Variétés
Route::resource('varietes', VarieteController::class);
Route::post('varietes/{variete}/prix', [VarieteController::class, 'updatePrix'])->name('varietes.prix.update');

// Récoltes
Route::resource('recoltes', RecolteController::class);

// Ventes
Route::resource('ventes', VenteController::class);

// Pertes
Route::resource('pertes', PerteController::class);