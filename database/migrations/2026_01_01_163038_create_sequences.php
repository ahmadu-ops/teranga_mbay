<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sequences = ['seq_produit', 'seq_variete', 'seq_prix', 'seq_recolte', 'seq_vente', 'seq_perte'];

        foreach ($sequences as $seq) {
            DB::unprepared("
                DECLARE
                    v_count NUMBER;
                BEGIN
                    SELECT COUNT(*) INTO v_count FROM user_sequences WHERE sequence_name = UPPER('{$seq}');
                    IF v_count = 0 THEN
                        EXECUTE IMMEDIATE 'CREATE SEQUENCE {$seq} START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE';
                    END IF;
                END;
            ");
        }
    }

    public function down(): void
    {
        $sequences = ['seq_produit', 'seq_variete', 'seq_prix', 'seq_recolte', 'seq_vente', 'seq_perte'];
        
        foreach ($sequences as $seq) {
            DB::unprepared("
                BEGIN
                    EXECUTE IMMEDIATE 'DROP SEQUENCE {$seq}';
                EXCEPTION
                    WHEN OTHERS THEN NULL;
                END;
            ");
        }
    }
};