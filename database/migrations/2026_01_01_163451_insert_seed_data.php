<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PRODUITS
        $produits = ['Chou', 'Carotte', 'Oignon', 'Aubergine', 'Piment', 'Tomate', 'Pomme de terre', 'Concombre'];
        foreach ($produits as $produit) {
            DB::table('produit')->insert(['nom_produit' => $produit]);
        }

        // VARIETES
        $varietes = [
            ['nom' => 'Chou vert', 'desc' => 'Chou classique pour salades', 'produit' => 1],
            ['nom' => 'Chou rouge', 'desc' => 'Riche en antioxydants', 'produit' => 1],
            ['nom' => 'Chou-fleur', 'desc' => 'Bouquets blancs populaires', 'produit' => 1],
            ['nom' => 'Brocoli', 'desc' => 'Inflorescences riches en vitamines', 'produit' => 1],
            ['nom' => 'Chou de Bruxelles', 'desc' => 'Bourgeons comestibles', 'produit' => 1],
            ['nom' => 'Chou romanesco', 'desc' => 'Forme spirale, texture croquante', 'produit' => 1],
            ['nom' => 'Chou chinois', 'desc' => 'Pak-choi, cuisine asiatique', 'produit' => 1],
            ['nom' => 'Carotte nantaise', 'desc' => 'Carotte douce et sucree', 'produit' => 2],
            ['nom' => 'Carotte violette', 'desc' => 'Riche en anthocyanes', 'produit' => 2],
            ['nom' => 'Oignon rouge', 'desc' => 'Saveur douce', 'produit' => 3],
            ['nom' => 'Oignon jaune', 'desc' => 'Oignon classique', 'produit' => 3],
            ['nom' => 'Aubergine noire', 'desc' => 'Variete classique', 'produit' => 4],
            ['nom' => 'Aubergine Thai', 'desc' => 'Petite aubergine verte', 'produit' => 4],
            ['nom' => 'Piment antillais', 'desc' => 'Tres piquant', 'produit' => 5],
            ['nom' => 'Piment doux', 'desc' => 'Saveur fruitee', 'produit' => 5],
            ['nom' => 'Tomate cerise', 'desc' => 'Petite et sucree', 'produit' => 6],
            ['nom' => 'Tomate coeur de boeuf', 'desc' => 'Grosse tomate charnue', 'produit' => 6],
            ['nom' => 'Pomme de terre Roseval', 'desc' => 'Peau rose, chair jaune', 'produit' => 7],
            ['nom' => 'Concombre classique', 'desc' => 'Long et vert fonce', 'produit' => 8],
        ];
        foreach ($varietes as $v) {
            DB::table('variete')->insert([
                'nom_variete' => $v['nom'],
                'description' => $v['desc'],
                'id_produit' => $v['produit']
            ]);
        }

        // PRIX (FCFA/kg)
        $prix = [
            1 => 400, 2 => 500, 3 => 600, 4 => 650, 5 => 700,
            6 => 750, 7 => 550, 8 => 300, 9 => 350, 10 => 250,
            11 => 200, 12 => 800, 13 => 450, 14 => 1200, 15 => 900,
            16 => 500, 17 => 700, 18 => 350, 19 => 300
        ];
        foreach ($prix as $idVariete => $prixKg) {
            DB::table('prix')->insert([
                'prix_kg' => $prixKg,
                'date_debut' => '2025-01-01',
                'date_fin' => null,
                'id_variete' => $idVariete
            ]);
        }

        // RECOLTES
        $recoltes = [
            ['date' => '2025-01-15', 'qte' => 150.5, 'variete' => 1],
            ['date' => '2025-01-16', 'qte' => 120.0, 'variete' => 2],
            ['date' => '2025-01-17', 'qte' => 95.5, 'variete' => 3],
            ['date' => '2025-01-18', 'qte' => 80.0, 'variete' => 4],
            ['date' => '2025-01-19', 'qte' => 60.0, 'variete' => 5],
            ['date' => '2025-01-20', 'qte' => 75.0, 'variete' => 6],
            ['date' => '2025-01-21', 'qte' => 88.5, 'variete' => 7],
            ['date' => '2025-01-22', 'qte' => 200.0, 'variete' => 8],
            ['date' => '2025-01-23', 'qte' => 180.0, 'variete' => 9],
            ['date' => '2025-01-24', 'qte' => 250.0, 'variete' => 10],
            ['date' => '2025-01-25', 'qte' => 300.0, 'variete' => 11],
            ['date' => '2025-01-26', 'qte' => 70.0, 'variete' => 12],
            ['date' => '2025-01-27', 'qte' => 55.0, 'variete' => 13],
            ['date' => '2025-01-28', 'qte' => 40.0, 'variete' => 14],
            ['date' => '2025-01-29', 'qte' => 45.0, 'variete' => 15],
            ['date' => '2025-01-30', 'qte' => 160.0, 'variete' => 16],
            ['date' => '2025-01-31', 'qte' => 140.0, 'variete' => 17],
            ['date' => '2025-02-01', 'qte' => 280.0, 'variete' => 18],
            ['date' => '2025-02-02', 'qte' => 220.0, 'variete' => 19],
        ];
        foreach ($recoltes as $r) {
            DB::table('recolte')->insert([
                'date_recolte' => $r['date'],
                'quantite_recolte' => $r['qte'],
                'id_variete' => $r['variete']
            ]);
        }

        // VENTES
        $ventes = [
            ['date' => '2025-01-16', 'qte' => 50.0, 'client' => 'Mamadou Diallo', 'variete' => 1],
            ['date' => '2025-01-17', 'qte' => 40.0, 'client' => 'Awa Ndiaye', 'variete' => 2],
            ['date' => '2025-01-18', 'qte' => 35.0, 'client' => 'Cheikh Fall', 'variete' => 3],
            ['date' => '2025-01-19', 'qte' => 30.0, 'client' => 'Fatou Sow', 'variete' => 4],
            ['date' => '2025-01-20', 'qte' => 25.0, 'client' => 'Ousmane Ba', 'variete' => 5],
            ['date' => '2025-01-21', 'qte' => 45.0, 'client' => 'Ibrahima Kane', 'variete' => 6],
            ['date' => '2025-01-22', 'qte' => 55.0, 'client' => 'Khady Faye', 'variete' => 7],
            ['date' => '2025-01-23', 'qte' => 100.0, 'client' => 'Serigne Sarr', 'variete' => 8],
            ['date' => '2025-01-24', 'qte' => 90.0, 'client' => 'Sokhna Mbaye', 'variete' => 9],
            ['date' => '2025-01-25', 'qte' => 120.0, 'client' => 'Abdoulaye Diop', 'variete' => 10],
            ['date' => '2025-01-26', 'qte' => 150.0, 'client' => 'Modou Gueye', 'variete' => 11],
            ['date' => '2025-01-27', 'qte' => 35.0, 'client' => 'Aminata Diaw', 'variete' => 12],
            ['date' => '2025-01-28', 'qte' => 25.0, 'client' => 'Moussa Seck', 'variete' => 13],
            ['date' => '2025-01-29', 'qte' => 20.0, 'client' => 'Ndeye Fatou', 'variete' => 14],
            ['date' => '2025-01-30', 'qte' => 22.0, 'client' => 'Babacar Niang', 'variete' => 15],
        ];
        foreach ($ventes as $v) {
            DB::table('vente')->insert([
                'date_vente' => $v['date'],
                'quantite_vendu' => $v['qte'],
                'prix_unitaire' => 0,
                'montant_total' => 0,
                'nom_client' => $v['client'],
                'id_variete' => $v['variete']
            ]);
        }

        // PERTES
        $pertes = [
            ['date' => '2025-01-20', 'qte' => 5.0, 'motif' => 'Pourriture', 'variete' => 1],
            ['date' => '2025-01-21', 'qte' => 3.5, 'motif' => 'Vol', 'variete' => 2],
            ['date' => '2025-01-22', 'qte' => 4.0, 'motif' => 'Accident', 'variete' => 3],
            ['date' => '2025-01-23', 'qte' => 2.5, 'motif' => 'Pourriture', 'variete' => 4],
            ['date' => '2025-01-24', 'qte' => 8.0, 'motif' => 'Invendu', 'variete' => 8],
            ['date' => '2025-01-25', 'qte' => 6.0, 'motif' => 'Invendu', 'variete' => 9],
            ['date' => '2025-01-26', 'qte' => 10.0, 'motif' => 'Pourriture', 'variete' => 10],
            ['date' => '2025-01-27', 'qte' => 5.0, 'motif' => 'Accident', 'variete' => 11],
        ];
        foreach ($pertes as $p) {
            DB::table('perte')->insert([
                'date_perte' => $p['date'],
                'quantite_perdue' => $p['qte'],
                'motif_perte' => $p['motif'],
                'valeur_estimee' => 0,
                'id_variete' => $p['variete']
            ]);
        }
    }

    public function down(): void
    {
        DB::table('perte')->delete();
        DB::table('vente')->delete();
        DB::table('recolte')->delete();
        DB::table('prix')->delete();
        DB::table('variete')->delete();
        DB::table('produit')->delete();
    }
};