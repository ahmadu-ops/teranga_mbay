<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class OracleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configuration du format de date Oracle
        if (config('database.default') === 'oracle') {
            try {
                DB::statement("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
                DB::statement("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
                DB::statement("ALTER SESSION SET NLS_NUMERIC_CHARACTERS = '. '");
            } catch (\Exception $e) {
                // Ignorer si la connexion n'est pas encore établie
            }
        }
    }
}