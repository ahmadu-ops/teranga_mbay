<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // TRIGGERS AUTO-INCREMENT
        // ============================================================

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_produit_id
            BEFORE INSERT ON produit
            FOR EACH ROW
            BEGIN
                IF :NEW.id_produit IS NULL THEN
                    :NEW.id_produit := seq_produit.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_variete_id
            BEFORE INSERT ON variete
            FOR EACH ROW
            BEGIN
                IF :NEW.id_variete IS NULL THEN
                    :NEW.id_variete := seq_variete.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_prix_id
            BEFORE INSERT ON prix
            FOR EACH ROW
            BEGIN
                IF :NEW.id_prix IS NULL THEN
                    :NEW.id_prix := seq_prix.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_recolte_id
            BEFORE INSERT ON recolte
            FOR EACH ROW
            BEGIN
                IF :NEW.id_recolte IS NULL THEN
                    :NEW.id_recolte := seq_recolte.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_vente_id
            BEFORE INSERT ON vente
            FOR EACH ROW
            BEGIN
                IF :NEW.id_vente IS NULL THEN
                    :NEW.id_vente := seq_vente.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_perte_id
            BEFORE INSERT ON perte
            FOR EACH ROW
            BEGIN
                IF :NEW.id_perte IS NULL THEN
                    :NEW.id_perte := seq_perte.NEXTVAL;
                END IF;
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        // ============================================================
        // TRIGGERS UPDATE TIMESTAMP
        // ============================================================

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_produit_update
            BEFORE UPDATE ON produit
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_variete_update
            BEFORE UPDATE ON variete
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_prix_update
            BEFORE UPDATE ON prix
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_recolte_update
            BEFORE UPDATE ON recolte
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_vente_update
            BEFORE UPDATE ON vente
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_perte_update
            BEFORE UPDATE ON perte
            FOR EACH ROW
            BEGIN
                :NEW.updated_at := CURRENT_TIMESTAMP;
            END;
        ");

        // ============================================================
        // TRIGGERS MÉTIER
        // ============================================================

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_cloturer_ancien_prix
            BEFORE INSERT ON prix
            FOR EACH ROW
            BEGIN
                UPDATE prix
                SET date_fin = :NEW.date_debut - 1,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id_variete = :NEW.id_variete
                AND date_fin IS NULL;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_calcul_montant_vente
            BEFORE INSERT OR UPDATE ON vente
            FOR EACH ROW
            DECLARE
                v_prix NUMBER(10,2);
            BEGIN
                SELECT prix_kg INTO v_prix
                FROM prix
                WHERE id_variete = :NEW.id_variete
                AND date_fin IS NULL;
                
                :NEW.prix_unitaire := v_prix;
                :NEW.montant_total := v_prix * :NEW.quantite_vendu;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    RAISE_APPLICATION_ERROR(-20001, 
                        'Aucun prix actif pour la variete ID: ' || :NEW.id_variete);
                WHEN TOO_MANY_ROWS THEN
                    RAISE_APPLICATION_ERROR(-20002, 
                        'Plusieurs prix actifs pour la variete - donnees incoherentes');
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_calcul_valeur_perte
            BEFORE INSERT OR UPDATE ON perte
            FOR EACH ROW
            DECLARE
                v_prix NUMBER(10,2);
            BEGIN
                SELECT prix_kg INTO v_prix
                FROM prix
                WHERE id_variete = :NEW.id_variete
                AND date_fin IS NULL;
                
                :NEW.valeur_estimee := v_prix * :NEW.quantite_perdue;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    RAISE_APPLICATION_ERROR(-20003, 
                        'Aucun prix actif pour calculer la perte');
                WHEN TOO_MANY_ROWS THEN
                    RAISE_APPLICATION_ERROR(-20004, 
                        'Plusieurs prix actifs - donnees incoherentes');
            END;
        ");

        // TRIGGER CORRIGÉ: Validation du stock avant vente
        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_valider_stock_vente
            BEFORE INSERT ON vente
            FOR EACH ROW
            DECLARE
                v_total_recolte NUMBER(10,2);
                v_total_vendu NUMBER(10,2);
                v_total_perdu NUMBER(10,2);
                v_stock NUMBER(10,2);
            BEGIN
                -- Total récolté
                SELECT NVL(SUM(quantite_recolte), 0) 
                INTO v_total_recolte
                FROM recolte 
                WHERE id_variete = :NEW.id_variete;
                
                -- Total déjà vendu
                SELECT NVL(SUM(quantite_vendu), 0) 
                INTO v_total_vendu
                FROM vente 
                WHERE id_variete = :NEW.id_variete;
                
                -- Total perdu
                SELECT NVL(SUM(quantite_perdue), 0) 
                INTO v_total_perdu
                FROM perte 
                WHERE id_variete = :NEW.id_variete;
                
                -- Calcul du stock disponible
                v_stock := v_total_recolte - v_total_vendu - v_total_perdu;
                
                IF :NEW.quantite_vendu > v_stock THEN
                    RAISE_APPLICATION_ERROR(-20010, 
                        'Stock insuffisant! Disponible: ' || v_stock || 
                        ' kg, Demande: ' || :NEW.quantite_vendu || ' kg');
                END IF;
            END;
        ");

        // TRIGGER CORRIGÉ: Validation du stock avant perte
        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_valider_stock_perte
            BEFORE INSERT ON perte
            FOR EACH ROW
            DECLARE
                v_total_recolte NUMBER(10,2);
                v_total_vendu NUMBER(10,2);
                v_total_perdu NUMBER(10,2);
                v_stock NUMBER(10,2);
            BEGIN
                -- Total récolté
                SELECT NVL(SUM(quantite_recolte), 0) 
                INTO v_total_recolte
                FROM recolte 
                WHERE id_variete = :NEW.id_variete;
                
                -- Total vendu
                SELECT NVL(SUM(quantite_vendu), 0) 
                INTO v_total_vendu
                FROM vente 
                WHERE id_variete = :NEW.id_variete;
                
                -- Total déjà perdu
                SELECT NVL(SUM(quantite_perdue), 0) 
                INTO v_total_perdu
                FROM perte 
                WHERE id_variete = :NEW.id_variete;
                
                -- Calcul du stock disponible
                v_stock := v_total_recolte - v_total_vendu - v_total_perdu;
                
                IF :NEW.quantite_perdue > v_stock THEN
                    RAISE_APPLICATION_ERROR(-20011, 
                        'Incoherence: Perte (' || :NEW.quantite_perdue || 
                        ' kg) > Stock (' || v_stock || ' kg)');
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        $triggers = [
            'trg_produit_id', 'trg_variete_id', 'trg_prix_id', 
            'trg_recolte_id', 'trg_vente_id', 'trg_perte_id',
            'trg_produit_update', 'trg_variete_update', 'trg_prix_update',
            'trg_recolte_update', 'trg_vente_update', 'trg_perte_update',
            'trg_cloturer_ancien_prix', 'trg_calcul_montant_vente',
            'trg_calcul_valeur_perte', 'trg_valider_stock_vente', 'trg_valider_stock_perte'
        ];

        foreach ($triggers as $trigger) {
            DB::unprepared("
                BEGIN
                    EXECUTE IMMEDIATE 'DROP TRIGGER {$trigger}';
                EXCEPTION
                    WHEN OTHERS THEN NULL;
                END;
            ");
        }
    }
};