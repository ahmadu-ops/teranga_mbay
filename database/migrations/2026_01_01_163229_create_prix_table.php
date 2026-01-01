<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE prix (
                id_prix NUMBER PRIMARY KEY,
                prix_kg NUMBER(10,2) NOT NULL,
                date_debut DATE NOT NULL,
                date_fin DATE,
                id_variete NUMBER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_prix_variete 
                    FOREIGN KEY (id_variete) 
                    REFERENCES variete(id_variete) ON DELETE CASCADE,
                CONSTRAINT chk_prix_positif CHECK (prix_kg > 0),
                CONSTRAINT chk_dates_prix CHECK (date_fin IS NULL OR date_fin >= date_debut)
            )
        ");

        DB::statement("CREATE INDEX idx_prix_variete ON prix(id_variete)");
        DB::statement("CREATE INDEX idx_prix_dates ON prix(date_debut, date_fin)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE prix CASCADE CONSTRAINTS");
    }
};