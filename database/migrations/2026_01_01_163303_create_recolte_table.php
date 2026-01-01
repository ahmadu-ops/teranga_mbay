<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE recolte (
                id_recolte NUMBER PRIMARY KEY,
                date_recolte DATE NOT NULL,
                quantite_recolte NUMBER(10,2) NOT NULL,
                id_variete NUMBER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_recolte_variete 
                    FOREIGN KEY (id_variete) 
                    REFERENCES variete(id_variete) ON DELETE CASCADE,
                CONSTRAINT chk_qte_recolte CHECK (quantite_recolte > 0)
            )
        ");

        DB::statement("CREATE INDEX idx_recolte_variete ON recolte(id_variete)");
        DB::statement("CREATE INDEX idx_recolte_date ON recolte(date_recolte)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE recolte CASCADE CONSTRAINTS");
    }
};