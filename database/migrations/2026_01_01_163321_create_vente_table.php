<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE vente (
                id_vente NUMBER PRIMARY KEY,
                date_vente DATE NOT NULL,
                quantite_vendu NUMBER(10,2) NOT NULL,
                prix_unitaire NUMBER(10,2) NOT NULL,
                montant_total NUMBER(12,2) NOT NULL,
                nom_client VARCHAR2(100),
                id_variete NUMBER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_vente_variete 
                    FOREIGN KEY (id_variete) 
                    REFERENCES variete(id_variete) ON DELETE CASCADE,
                CONSTRAINT chk_qte_vente CHECK (quantite_vendu > 0),
                CONSTRAINT chk_montant_vente CHECK (montant_total >= 0)
            )
        ");

        DB::statement("CREATE INDEX idx_vente_variete ON vente(id_variete)");
        DB::statement("CREATE INDEX idx_vente_date ON vente(date_vente)");
        DB::statement("CREATE INDEX idx_vente_client ON vente(nom_client)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE vente CASCADE CONSTRAINTS");
    }
};