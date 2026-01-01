<?php

namespace App\Providers;

use App\Models\Produit;
use App\Models\Variete;
use App\Models\Recolte;
use App\Models\Vente;
use App\Models\Perte;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Route Model Binding avec clés personnalisées
        Route::model('produit', Produit::class);
        Route::model('variete', Variete::class);
        Route::model('recolte', Recolte::class);
        Route::model('vente', Vente::class);
        Route::model('perte', Perte::class);

        // Binding personnalisé pour les clés primaires Oracle
        Route::bind('produit', fn($value) => Produit::where('id_produit', $value)->firstOrFail());
        Route::bind('variete', fn($value) => Variete::where('id_variete', $value)->firstOrFail());
        Route::bind('recolte', fn($value) => Recolte::where('id_recolte', $value)->firstOrFail());
        Route::bind('vente', fn($value) => Vente::where('id_vente', $value)->firstOrFail());
        Route::bind('perte', fn($value) => Perte::where('id_perte', $value)->firstOrFail());
    }
}