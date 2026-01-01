<?php

namespace App\Console\Commands;

use App\Models\Produit;
use App\Models\Variete;
use App\Models\Prix;
use App\Models\Recolte;
use App\Models\Vente;
use App\Models\Perte;
use App\Models\Views\VDashboardResume;
use App\Models\Views\VStockActuel;
use App\Models\Views\VRentabiliteVariete;
use Illuminate\Console\Command;

class TestModels extends Command
{
    protected $signature = 'test:models';
    protected $description = 'Tester les modèles Eloquent';

    public function handle()
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║           🌾 TERANGA MBAY - Test des Modèles               ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');

        // Test Produit
        $this->info('📦 PRODUITS');
        $produits = Produit::with('varietes')->get();
        foreach ($produits as $produit) {
            $this->line("   {$produit->nom_produit} ({$produit->nombre_varietes} variétés)");
        }

        // Test Variété avec relations
        $this->newLine();
        $this->info('🌱 VARIÉTÉS (5 premières avec stock)');
        $varietes = Variete::with(['produit', 'prixActuel'])->take(5)->get();
        foreach ($varietes as $variete) {
            $prix = $variete->prix_actuel_value ?? 0;
            $stock = $variete->stock_disponible;
            $this->line("   {$variete->nom_complet}: {$stock} kg @ {$prix} FCFA/kg");
        }

        // Test Vue Dashboard
        $this->newLine();
        $this->info('📊 DASHBOARD');
        $dashboard = VDashboardResume::getData();
        if ($dashboard) {
            $this->table(
                ['Métrique', 'Valeur'],
                [
                    ['Produits', $dashboard->nb_produits],
                    ['Variétés', $dashboard->nb_varietes],
                    ['Récoltes (kg)', number_format($dashboard->total_recolte_kg, 2)],
                    ['Ventes (kg)', number_format($dashboard->total_ventes_kg, 2)],
                    ['CA (FCFA)', number_format($dashboard->chiffre_affaires, 0, ',', ' ')],
                    ['Pertes (kg)', number_format($dashboard->total_pertes_kg, 2)],
                    ['Stock (kg)', number_format($dashboard->stock_total_kg, 2)],
                ]
            );
        }

        // Test Vue Stock
        $this->newLine();
        $this->info('📈 TOP 5 STOCKS');
        $stocks = VStockActuel::avecStock()
            ->orderBy('valeur_stock', 'desc')
            ->take(5)
            ->get();
        
        $tableData = [];
        foreach ($stocks as $stock) {
            $tableData[] = [
                $stock->nom_variete,
                number_format($stock->stock_disponible, 2) . ' kg',
                number_format($stock->valeur_stock, 0, ',', ' ') . ' FCFA'
            ];
        }
        $this->table(['Variété', 'Stock', 'Valeur'], $tableData);

        // Test Rentabilité
        $this->newLine();
        $this->info('💰 TOP 5 RENTABILITÉ');
        $rentabilites = VRentabiliteVariete::rentable()
            ->orderBy('benefice_net', 'desc')
            ->take(5)
            ->get();
        
        $tableData = [];
        foreach ($rentabilites as $r) {
            $tableData[] = [
                $r->nom_variete,
                number_format($r->revenus_ventes, 0, ',', ' ') . ' FCFA',
                number_format($r->benefice_net, 0, ',', ' ') . ' FCFA'
            ];
        }
        $this->table(['Variété', 'Revenus', 'Bénéfice'], $tableData);

        $this->newLine();
        $this->info('🎉 Tests des modèles terminés!');
        $this->newLine();

        return Command::SUCCESS;
    }
}