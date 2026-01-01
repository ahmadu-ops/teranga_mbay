<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE variete (
                id_variete NUMBER PRIMARY KEY,
                nom_variete VARCHAR2(50) NOT NULL,
                description VARCHAR2(255),
                id_produit NUMBER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_variete_produit 
                    FOREIGN KEY (id_produit) 
                    REFERENCES produit(id_produit) ON DELETE CASCADE,
                CONSTRAINT uk_variete_nom_produit UNIQUE (nom_variete, id_produit)
            )
        ");

        DB::statement("CREATE INDEX idx_variete_produit ON variete(id_produit)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE variete CASCADE CONSTRAINTS");
    }
};