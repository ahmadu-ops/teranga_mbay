<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE perte (
                id_perte NUMBER PRIMARY KEY,
                date_perte DATE NOT NULL,
                quantite_perdue NUMBER(10,2) NOT NULL,
                motif_perte VARCHAR2(100) NOT NULL,
                valeur_estimee NUMBER(12,2) NOT NULL,
                id_variete NUMBER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_perte_variete 
                    FOREIGN KEY (id_variete) 
                    REFERENCES variete(id_variete) ON DELETE CASCADE,
                CONSTRAINT chk_qte_perte CHECK (quantite_perdue > 0),
                CONSTRAINT chk_motif_perte CHECK (
                    motif_perte IN ('Pourriture', 'Vol', 'Accident', 'Invendu', 'Autre')
                )
            )
        ");

        DB::statement("CREATE INDEX idx_perte_variete ON perte(id_variete)");
        DB::statement("CREATE INDEX idx_perte_date ON perte(date_perte)");
        DB::statement("CREATE INDEX idx_perte_motif ON perte(motif_perte)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE perte CASCADE CONSTRAINTS");
    }
};