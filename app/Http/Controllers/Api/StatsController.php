<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\VenteService;
use App\Services\RecolteService;
use App\Services\PerteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private VenteService $venteService,
        private RecolteService $recolteService,
        private PerteService $perteService
    ) {}

    /**
     * KPIs pour le dashboard
     */
    public function kpis(): JsonResponse
    {
        return response()->json($this->dashboardService->getKPIs());
    }

    /**
     * Données pour graphique des récoltes
     */
    public function graphiqueRecoltes(): JsonResponse
    {
        return response()->json($this->dashboardService->getGraphiqueRecoltes());
    }

    /**
     * Données pour graphique des ventes
     */
    public function graphiqueVentes(): JsonResponse
    {
        return response()->json($this->dashboardService->getGraphiqueVentes());
    }

    /**
     * Données pour graphique des pertes
     */
    public function graphiquePertes(): JsonResponse
    {
        return response()->json($this->dashboardService->getGraphiquePertes());
    }

    /**
     * Répartition du stock par produit
     */
    public function repartitionStock(): JsonResponse
    {
        return response()->json($this->dashboardService->getRepartitionStockParProduit());
    }

    /**
     * Statistiques des ventes
     */
    public function statsVentes(Request $request): JsonResponse
    {
        return response()->json($this->venteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        ));
    }

    /**
     * Statistiques des récoltes
     */
    public function statsRecoltes(Request $request): JsonResponse
    {
        return response()->json($this->recolteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        ));
    }

    /**
     * Statistiques des pertes
     */
    public function statsPertes(Request $request): JsonResponse
    {
        return response()->json($this->perteService->getStats(
            $request->get('date_debut'),
            $request->get('date_fin')
        ));
    }

    /**
     * Statistiques des pertes par motif
     */
    public function statspertesParMotif(): JsonResponse
    {
        return response()->json($this->perteService->getStatsParMotif());
    }
}