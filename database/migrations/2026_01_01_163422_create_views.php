<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW v_stock_actuel AS
            SELECT 
                p.id_produit,
                p.nom_produit,
                v.id_variete,
                v.nom_variete,
                v.description,
                NVL(r.total_recolte, 0) AS total_recolte,
                NVL(ve.total_vendu, 0) AS total_vendu,
                NVL(pe.total_perdu, 0) AS total_perdu,
                NVL(r.total_recolte, 0) - NVL(ve.total_vendu, 0) - NVL(pe.total_perdu, 0) AS stock_disponible,
                pr.prix_kg AS prix_actuel,
                (NVL(r.total_recolte, 0) - NVL(ve.total_vendu, 0) - NVL(pe.total_perdu, 0)) * NVL(pr.prix_kg, 0) AS valeur_stock
            FROM variete v
            INNER JOIN produit p ON v.id_produit = p.id_produit
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_recolte) AS total_recolte
                FROM recolte GROUP BY id_variete
            ) r ON v.id_variete = r.id_variete
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_vendu) AS total_vendu
                FROM vente GROUP BY id_variete
            ) ve ON v.id_variete = ve.id_variete
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_perdue) AS total_perdu
                FROM perte GROUP BY id_variete
            ) pe ON v.id_variete = pe.id_variete
            LEFT JOIN prix pr ON v.id_variete = pr.id_variete AND pr.date_fin IS NULL
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_dashboard_resume AS
            SELECT
                (SELECT COUNT(*) FROM produit) AS nb_produits,
                (SELECT COUNT(*) FROM variete) AS nb_varietes,
                (SELECT NVL(SUM(quantite_recolte), 0) FROM recolte) AS total_recolte_kg,
                (SELECT NVL(SUM(quantite_vendu), 0) FROM vente) AS total_ventes_kg,
                (SELECT NVL(SUM(montant_total), 0) FROM vente) AS chiffre_affaires,
                (SELECT NVL(SUM(quantite_perdue), 0) FROM perte) AS total_pertes_kg,
                (SELECT NVL(SUM(valeur_estimee), 0) FROM perte) AS valeur_pertes,
                (SELECT NVL(SUM(stock_disponible), 0) FROM v_stock_actuel) AS stock_total_kg,
                (SELECT NVL(SUM(valeur_stock), 0) FROM v_stock_actuel) AS valeur_stock_total
            FROM DUAL
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_mouvements_stock AS
            SELECT 
                'RECOLTE' AS type_mouvement,
                r.id_recolte AS id_mouvement,
                r.date_recolte AS date_mouvement,
                v.nom_variete,
                p.nom_produit,
                r.quantite_recolte AS quantite,
                NULL AS montant,
                'Entree en stock' AS description
            FROM recolte r
            JOIN variete v ON r.id_variete = v.id_variete
            JOIN produit p ON v.id_produit = p.id_produit
            UNION ALL
            SELECT 
                'VENTE' AS type_mouvement,
                ve.id_vente AS id_mouvement,
                ve.date_vente AS date_mouvement,
                v.nom_variete,
                p.nom_produit,
                -ve.quantite_vendu AS quantite,
                ve.montant_total AS montant,
                'Vente a ' || NVL(ve.nom_client, 'Client anonyme') AS description
            FROM vente ve
            JOIN variete v ON ve.id_variete = v.id_variete
            JOIN produit p ON v.id_produit = p.id_produit
            UNION ALL
            SELECT 
                'PERTE' AS type_mouvement,
                pe.id_perte AS id_mouvement,
                pe.date_perte AS date_mouvement,
                v.nom_variete,
                p.nom_produit,
                -pe.quantite_perdue AS quantite,
                -pe.valeur_estimee AS montant,
                'Perte: ' || pe.motif_perte AS description
            FROM perte pe
            JOIN variete v ON pe.id_variete = v.id_variete
            JOIN produit p ON v.id_produit = p.id_produit
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_rentabilite_variete AS
            SELECT 
                p.nom_produit,
                v.id_variete,
                v.nom_variete,
                NVL(r.total_recolte, 0) AS quantite_recoltee,
                NVL(ve.total_vendu, 0) AS quantite_vendue,
                NVL(ve.revenus, 0) AS revenus_ventes,
                NVL(pe.total_perdu, 0) AS quantite_perdue,
                NVL(pe.valeur_perdue, 0) AS valeur_pertes,
                NVL(ve.revenus, 0) - NVL(pe.valeur_perdue, 0) AS benefice_net,
                CASE 
                    WHEN NVL(r.total_recolte, 0) > 0 
                    THEN ROUND(NVL(ve.total_vendu, 0) / r.total_recolte * 100, 2)
                    ELSE 0 
                END AS taux_vente_pct,
                CASE 
                    WHEN NVL(r.total_recolte, 0) > 0 
                    THEN ROUND(NVL(pe.total_perdu, 0) / r.total_recolte * 100, 2)
                    ELSE 0 
                END AS taux_perte_pct
            FROM variete v
            JOIN produit p ON v.id_produit = p.id_produit
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_recolte) AS total_recolte
                FROM recolte GROUP BY id_variete
            ) r ON v.id_variete = r.id_variete
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_vendu) AS total_vendu, SUM(montant_total) AS revenus
                FROM vente GROUP BY id_variete
            ) ve ON v.id_variete = ve.id_variete
            LEFT JOIN (
                SELECT id_variete, SUM(quantite_perdue) AS total_perdu, SUM(valeur_estimee) AS valeur_perdue
                FROM perte GROUP BY id_variete
            ) pe ON v.id_variete = pe.id_variete
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_stats_mensuelles AS
            SELECT 
                TO_CHAR(date_recolte, 'YYYY-MM') AS periode,
                'RECOLTE' AS type_operation,
                COUNT(*) AS nb_operations,
                SUM(quantite_recolte) AS quantite_totale,
                NULL AS montant_total
            FROM recolte
            GROUP BY TO_CHAR(date_recolte, 'YYYY-MM')
            UNION ALL
            SELECT 
                TO_CHAR(date_vente, 'YYYY-MM') AS periode,
                'VENTE' AS type_operation,
                COUNT(*) AS nb_operations,
                SUM(quantite_vendu) AS quantite_totale,
                SUM(montant_total) AS montant_total
            FROM vente
            GROUP BY TO_CHAR(date_vente, 'YYYY-MM')
            UNION ALL
            SELECT 
                TO_CHAR(date_perte, 'YYYY-MM') AS periode,
                'PERTE' AS type_operation,
                COUNT(*) AS nb_operations,
                SUM(quantite_perdue) AS quantite_totale,
                SUM(valeur_estimee) AS montant_total
            FROM perte
            GROUP BY TO_CHAR(date_perte, 'YYYY-MM')
        ");

        DB::statement("
            CREATE OR REPLACE VIEW v_top_ventes AS
            SELECT 
                p.nom_produit,
                v.nom_variete,
                SUM(ve.quantite_vendu) AS total_vendu_kg,
                SUM(ve.montant_total) AS total_revenus,
                COUNT(ve.id_vente) AS nb_ventes,
                ROUND(AVG(ve.prix_unitaire), 2) AS prix_moyen
            FROM vente ve
            JOIN variete v ON ve.id_variete = v.id_variete
            JOIN produit p ON v.id_produit = p.id_produit
            GROUP BY p.nom_produit, v.nom_variete
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW v_top_ventes");
        DB::statement("DROP VIEW v_stats_mensuelles");
        DB::statement("DROP VIEW v_rentabilite_variete");
        DB::statement("DROP VIEW v_mouvements_stock");
        DB::statement("DROP VIEW v_dashboard_resume");
        DB::statement("DROP VIEW v_stock_actuel");
    }
};