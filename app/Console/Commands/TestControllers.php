<?php

namespace App\Console\Commands;

use App\Services\DashboardService;
use App\Services\ProduitService;
use App\Services\VarieteService;
use App\Services\VenteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class TestControllers extends Command
{
    protected $signature = 'test:controllers';
    protected $description = 'Tester les services et contrôleurs';

    public function handle()
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║        🌾 TERANGA MBAY - Test Services & Contrôleurs       ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');

        // Test DashboardService
        $this->info('📊 DashboardService');
        $dashboardService = app(DashboardService::class);
        $kpis = $dashboardService->getKPIs();
        $this->line('   ✓ KPIs: ' . count($kpis) . ' métriques');
        $this->line('   ✓ Stocks: ' . $dashboardService->getStocks()->count() . ' variétés');
        $this->line('   ✓ Top Ventes: ' . $dashboardService->getTopVentes(5)->count() . ' items');

        // Test ProduitService
        $this->newLine();
        $this->info('📦 ProduitService');
        $produitService = app(ProduitService::class);
        $produits = $produitService->paginate(10);
        $this->line('   ✓ Pagination: ' . $produits->total() . ' produits');

        // Test VarieteService
        $this->newLine();
        $this->info('🌱 VarieteService');
        $varieteService = app(VarieteService::class);
        $varietes = $varieteService->all();
        $avecStock = $varieteService->avecStock();
        $this->line('   ✓ Total: ' . $varietes->count() . ' variétés');
        $this->line('   ✓ Avec stock: ' . $avecStock->count() . ' variétés');

        // Test VenteService
        $this->newLine();
        $this->info('💰 VenteService');
        $venteService = app(VenteService::class);
        $stats = $venteService->getStats();
        $this->line('   ✓ CA: ' . number_format($stats['chiffre_affaires'], 0, ',', ' ') . ' FCFA');
        $this->line('   ✓ Ventes: ' . $stats['nombre_ventes']);

        // Test Routes
        $this->newLine();
        $this->info('🛤️ Routes');
        $routes = collect(Route::getRoutes())->filter(fn($r) => str_starts_with($r->uri, 'produits') 
            || str_starts_with($r->uri, 'varietes')
            || str_starts_with($r->uri, 'recoltes')
            || str_starts_with($r->uri, 'ventes')
            || str_starts_with($r->uri, 'pertes')
            || str_starts_with($r->uri, 'api/stats')
            || $r->uri === '/'
        );
        $this->line('   ✓ ' . $routes->count() . ' routes définies');

        $this->newLine();
        $this->info('🎉 Tous les tests ont réussi!');
        $this->newLine();

        return Command::SUCCESS;
    }
}