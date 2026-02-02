<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE produit (
                id_produit NUMBER PRIMARY KEY,
                nom_produit VARCHAR2(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT uk_produit_nom UNIQUE (nom_produit)
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE produit CASCADE CONSTRAINTS");
    }
};