<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'kpis' => $this->dashboardService->getKPIs(),
            'stocks' => $this->dashboardService->getStocks(),
            'topVentes' => $this->dashboardService->getTopVentes(5),
            'rentabilite' => $this->dashboardService->getRentabilite()->take(5),
            'mouvements' => $this->dashboardService->getMouvementsRecents(10),
            'graphiqueRecoltes' => $this->dashboardService->getGraphiqueRecoltes(),
            'graphiqueVentes' => $this->dashboardService->getGraphiqueVentes(),
            'repartitionStock' => $this->dashboardService->getRepartitionStockParProduit(),
        ]);
    }
}