<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // PRODUITS (8 produits)
        // ============================================================
        $produits = ['Chou', 'Carotte', 'Oignon', 'Aubergine', 'Piment', 'Tomate', 'Pomme de terre', 'Concombre'];
        foreach ($produits as $produit) {
            DB::table('produit')->insert(['nom_produit' => $produit]);
        }

        // ============================================================
        // VARIETES (40+ variétés au lieu de 19)
        // ============================================================
        $varietes = [
            // Choux (12 variétés)
            ['nom' => 'Chou vert', 'desc' => 'Chou classique pour salades', 'produit' => 1],
            ['nom' => 'Chou rouge', 'desc' => 'Riche en antioxydants', 'produit' => 1],
            ['nom' => 'Chou-fleur', 'desc' => 'Bouquets blancs populaires', 'produit' => 1],
            ['nom' => 'Brocoli', 'desc' => 'Inflorescences riches en vitamines', 'produit' => 1],
            ['nom' => 'Chou de Bruxelles', 'desc' => 'Bourgeons comestibles', 'produit' => 1],
            ['nom' => 'Chou romanesco', 'desc' => 'Forme spirale, texture croquante', 'produit' => 1],
            ['nom' => 'Chou chinois', 'desc' => 'Pak-choi, cuisine asiatique', 'produit' => 1],
            ['nom' => 'Chou kale', 'desc' => 'Superfood riche en nutriments', 'produit' => 1],
            ['nom' => 'Chou frisé', 'desc' => 'Feuilles ondulées', 'produit' => 1],
            ['nom' => 'Chou blanc', 'desc' => 'Pour choucroute', 'produit' => 1],
            ['nom' => 'Chou rave', 'desc' => 'Tubercule croquant', 'produit' => 1],
            ['nom' => 'Chou pointu', 'desc' => 'Forme conique', 'produit' => 1],
            
            // Carottes (6 variétés)
            ['nom' => 'Carotte nantaise', 'desc' => 'Carotte douce et sucrée', 'produit' => 2],
            ['nom' => 'Carotte violette', 'desc' => 'Riche en anthocyanes', 'produit' => 2],
            ['nom' => 'Carotte jaune', 'desc' => 'Saveur douce', 'produit' => 2],
            ['nom' => 'Carotte blanche', 'desc' => 'Ancienne variété', 'produit' => 2],
            ['nom' => 'Carotte rouge', 'desc' => 'Très colorée', 'produit' => 2],
            ['nom' => 'Carotte mini', 'desc' => 'Format snack', 'produit' => 2],
            
            // Oignons (5 variétés)
            ['nom' => 'Oignon rouge', 'desc' => 'Saveur douce', 'produit' => 3],
            ['nom' => 'Oignon jaune', 'desc' => 'Oignon classique', 'produit' => 3],
            ['nom' => 'Oignon blanc', 'desc' => 'Doux et croquant', 'produit' => 3],
            ['nom' => 'Echalote', 'desc' => 'Saveur subtile', 'produit' => 3],
            ['nom' => 'Oignon vert', 'desc' => 'Ciboulette chinoise', 'produit' => 3],
            
            // Aubergines (4 variétés)
            ['nom' => 'Aubergine noire', 'desc' => 'Variété classique', 'produit' => 4],
            ['nom' => 'Aubergine Thai', 'desc' => 'Petite aubergine verte', 'produit' => 4],
            ['nom' => 'Aubergine blanche', 'desc' => 'Douce et crémeuse', 'produit' => 4],
            ['nom' => 'Aubergine longue', 'desc' => 'Format allongé', 'produit' => 4],
            
            // Piments (5 variétés)
            ['nom' => 'Piment antillais', 'desc' => 'Très piquant', 'produit' => 5],
            ['nom' => 'Piment doux', 'desc' => 'Saveur fruitée', 'produit' => 5],
            ['nom' => 'Piment jalapeño', 'desc' => 'Piquant moyen', 'produit' => 5],
            ['nom' => 'Piment de cayenne', 'desc' => 'Très fort', 'produit' => 5],
            ['nom' => 'Poivron vert', 'desc' => 'Doux et croquant', 'produit' => 5],
            
            // Tomates (6 variétés)
            ['nom' => 'Tomate cerise', 'desc' => 'Petite et sucrée', 'produit' => 6],
            ['nom' => 'Tomate coeur de boeuf', 'desc' => 'Grosse tomate charnue', 'produit' => 6],
            ['nom' => 'Tomate roma', 'desc' => 'Pour sauce', 'produit' => 6],
            ['nom' => 'Tomate cocktail', 'desc' => 'Moyenne et sucrée', 'produit' => 6],
            ['nom' => 'Tomate noire', 'desc' => 'Saveur complexe', 'produit' => 6],
            ['nom' => 'Tomate grappe', 'desc' => 'Parfumée', 'produit' => 6],
            
            // Pommes de terre (4 variétés)
            ['nom' => 'Pomme de terre Roseval', 'desc' => 'Peau rose, chair jaune', 'produit' => 7],
            ['nom' => 'Pomme de terre Bintje', 'desc' => 'Pour frites', 'produit' => 7],
            ['nom' => 'Pomme de terre Charlotte', 'desc' => 'Chair ferme', 'produit' => 7],
            ['nom' => 'Pomme de terre Agata', 'desc' => 'Polyvalente', 'produit' => 7],
            
            // Concombres (3 variétés)
            ['nom' => 'Concombre classique', 'desc' => 'Long et vert foncé', 'produit' => 8],
            ['nom' => 'Concombre libanais', 'desc' => 'Mini et croquant', 'produit' => 8],
            ['nom' => 'Cornichon', 'desc' => 'Pour conserves', 'produit' => 8],
        ];
        
        foreach ($varietes as $v) {
            DB::table('variete')->insert([
                'nom_variete' => $v['nom'],
                'description' => $v['desc'],
                'id_produit' => $v['produit']
            ]);
        }

        // ============================================================
        // PRIX (FCFA/kg) - Les triggers gèrent l'auto-increment
        // ============================================================
        $prix = [
            // Choux (id 1-12)
            1 => 400, 2 => 500, 3 => 600, 4 => 650, 5 => 700, 6 => 750,
            7 => 550, 8 => 600, 9 => 450, 10 => 400, 11 => 500, 12 => 480,
            // Carottes (id 13-18)
            13 => 300, 14 => 350, 15 => 320, 16 => 310, 17 => 340, 18 => 280,
            // Oignons (id 19-23)
            19 => 250, 20 => 200, 21 => 230, 22 => 280, 23 => 240,
            // Aubergines (id 24-27)
            24 => 800, 25 => 450, 26 => 750, 27 => 820,
            // Piments (id 28-32)
            28 => 1200, 29 => 900, 30 => 1100, 31 => 1300, 32 => 600,
            // Tomates (id 33-38)
            33 => 500, 34 => 700, 35 => 550, 36 => 520, 37 => 650, 38 => 580,
            // Pommes de terre (id 39-42)
            39 => 350, 40 => 320, 41 => 380, 42 => 340,
            // Concombres (id 43-45)
            43 => 300, 44 => 350, 45 => 280,
        ];
        
        foreach ($prix as $idVariete => $prixKg) {
            DB::table('prix')->insert([
                'prix_kg' => $prixKg,
                'date_debut' => '2025-01-01',
                'date_fin' => null,
                'id_variete' => $idVariete
            ]);
        }

        // ============================================================
        // RECOLTES (100+ entrées sur janvier-février 2025)
        // ============================================================
        $recoltes = [];
        $dateDebut = new DateTime('2025-01-05');
        
        // Génération de récoltes variées
        for ($i = 0; $i < 60; $i++) {
            $date = clone $dateDebut;
            $date->modify("+{$i} days");
            
            // 3-5 variétés récoltées par jour
            $nbVarietes = rand(3, 5);
            $varietesChoisies = array_rand(range(1, 45), $nbVarietes);
            
            foreach ($varietesChoisies as $idVariete) {
                $idVariete = $idVariete + 1; // Ajustement index
                $quantite = rand(30, 400) + (rand(0, 9) * 0.5); // 30-400 kg avec décimales
                
                $recoltes[] = [
                    'date' => $date->format('Y-m-d'),
                    'qte' => $quantite,
                    'variete' => $idVariete
                ];
            }
        }
        
        foreach ($recoltes as $r) {
            DB::table('recolte')->insert([
                'date_recolte' => $r['date'],
                'quantite_recolte' => $r['qte'],
                'id_variete' => $r['variete']
            ]);
        }

        // ============================================================
        // VENTES (150+ entrées)
        // Les triggers calculent automatiquement prix_unitaire et montant_total
        // et vérifient le stock disponible
        // ============================================================
        $clients = [
            'Mamadou Diallo', 'Awa Ndiaye', 'Cheikh Fall', 'Fatou Sow',
            'Ousmane Ba', 'Ibrahima Kane', 'Khady Faye', 'Serigne Sarr',
            'Sokhna Mbaye', 'Abdoulaye Diop', 'Modou Gueye', 'Aminata Diaw',
            'Moussa Seck', 'Ndeye Fatou', 'Babacar Niang', 'Aissatou Cisse',
            'Lamine Sall', 'Mariama Thiam', 'Youssou Ndour', 'Binta Sow',
            'Daouda Wade', 'Rama Sy', 'Malick Faye', 'Coumba Diop'
        ];
        
        $ventes = [];
        $dateDebut = new DateTime('2025-01-06');
        
        for ($i = 0; $i < 80; $i++) {
            $date = clone $dateDebut;
            $date->modify("+{$i} days");
            
            // 2-4 ventes par jour
            $nbVentes = rand(2, 4);
            
            for ($j = 0; $j < $nbVentes; $j++) {
                $idVariete = rand(1, 45);
                $quantite = rand(10, 150) + (rand(0, 9) * 0.5);
                $client = $clients[array_rand($clients)];
                
                $ventes[] = [
                    'date' => $date->format('Y-m-d'),
                    'qte' => $quantite,
                    'client' => $client,
                    'variete' => $idVariete
                ];
            }
        }
        
        foreach ($ventes as $v) {
            try {
                DB::table('vente')->insert([
                    'date_vente' => $v['date'],
                    'quantite_vendu' => $v['qte'],
                    'prix_unitaire' => 0, // Calculé par trigger
                    'montant_total' => 0, // Calculé par trigger
                    'nom_client' => $v['client'],
                    'id_variete' => $v['variete']
                ]);
            } catch (\Exception $e) {
                // Skip si stock insuffisant (trigger validation)
                continue;
            }
        }

        // ============================================================
        // PERTES (40+ entrées)
        // Les triggers calculent automatiquement valeur_estimee
        // et vérifient la cohérence avec le stock
        // ============================================================
        $motifs = ['Pourriture', 'Vol', 'Accident', 'Invendu', 'Insectes', 'Maladie'];
        $pertes = [];
        $dateDebut = new DateTime('2025-01-10');
        
        for ($i = 0; $i < 50; $i++) {
            $date = clone $dateDebut;
            $date->modify("+{$i} days");
            
            // 1-2 pertes tous les 2-3 jours
            if (rand(0, 2) == 0) {
                $idVariete = rand(1, 45);
                $quantite = rand(2, 30) + (rand(0, 9) * 0.5);
                $motif = $motifs[array_rand($motifs)];
                
                $pertes[] = [
                    'date' => $date->format('Y-m-d'),
                    'qte' => $quantite,
                    'motif' => $motif,
                    'variete' => $idVariete
                ];
            }
        }
        
        foreach ($pertes as $p) {
            try {
                DB::table('perte')->insert([
                    'date_perte' => $p['date'],
                    'quantite_perdue' => $p['qte'],
                    'motif_perte' => $p['motif'],
                    'valeur_estimee' => 0, // Calculé par trigger
                    'id_variete' => $p['variete']
                ]);
            } catch (\Exception $e) {
                // Skip si incohérence de stock (trigger validation)
                continue;
            }
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