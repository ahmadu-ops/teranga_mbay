<?php

namespace App\Services;

use App\Models\Views\VDashboardResume;
use App\Models\Views\VStockActuel;
use App\Models\Views\VRentabiliteVariete;
use App\Models\Views\VMouvementsStock;
use App\Models\Views\VStatsMensuelles;
use App\Models\Views\VTopVentes;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Données principales du dashboard
     */
    public function getResume(): ?VDashboardResume
    {
        return VDashboardResume::getData();
    }

    /**
     * KPIs formatés
     */
    public function getKPIs(): array
    {
        $resume = $this->getResume();
        
        if (!$resume) {
            return [];
        }

        return [
            'produits' => [
                'valeur' => $resume->nb_produits,
                'label' => 'Produits',
                'icone' => 'box',
                'couleur' => 'primary',
            ],
            'varietes' => [
                'valeur' => $resume->nb_varietes,
                'label' => 'Variétés',
                'icone' => 'layers',
                'couleur' => 'info',
            ],
            'recoltes' => [
                'valeur' => number_format($resume->total_recolte_kg, 2, ',', ' ') . ' kg',
                'label' => 'Total Récolté',
                'icone' => 'trending-up',
                'couleur' => 'success',
            ],
            'ventes' => [
                'valeur' => number_format($resume->total_ventes_kg, 2, ',', ' ') . ' kg',
                'label' => 'Total Vendu',
                'icone' => 'shopping-cart',
                'couleur' => 'warning',
            ],
            'chiffre_affaires' => [
                'valeur' => number_format($resume->chiffre_affaires, 0, ',', ' ') . ' FCFA',
                'label' => 'Chiffre d\'Affaires',
                'icone' => 'dollar-sign',
                'couleur' => 'success',
            ],
            'pertes' => [
                'valeur' => number_format($resume->total_pertes_kg, 2, ',', ' ') . ' kg',
                'label' => 'Pertes',
                'icone' => 'alert-triangle',
                'couleur' => 'danger',
            ],
            'stock' => [
                'valeur' => number_format($resume->stock_total_kg, 2, ',', ' ') . ' kg',
                'label' => 'Stock Disponible',
                'icone' => 'package',
                'couleur' => 'primary',
            ],
            'valeur_stock' => [
                'valeur' => number_format($resume->valeur_stock_total, 0, ',', ' ') . ' FCFA',
                'label' => 'Valeur du Stock',
                'icone' => 'database',
                'couleur' => 'info',
            ],
        ];
    }

    /**
     * Stocks par variété
     */
    public function getStocks(bool $avecStockUniquement = true): Collection
    {
        $query = VStockActuel::query();
        
        if ($avecStockUniquement) {
            $query->avecStock();
        }
        
        return $query->orderBy('valeur_stock', 'desc')->get();
    }

    /**
     * Top variétés par ventes
     */
    public function getTopVentes(int $limit = 10): Collection
    {
        return VTopVentes::top($limit)->get();
    }

    /**
     * Rentabilité par variété
     */
    public function getRentabilite(): Collection
    {
        return VRentabiliteVariete::orderBy('benefice_net', 'desc')->get();
    }

    /**
     * Mouvements récents
     */
    public function getMouvementsRecents(int $limit = 20): Collection
    {
        return VMouvementsStock::recent()->limit($limit)->get();
    }

    /**
     * Stats mensuelles
     */
    public function getStatsMensuelles(): Collection
    {
        return VStatsMensuelles::orderBy('periode', 'desc')->get();
    }

    /**
     * Données pour graphique des récoltes
     */
    public function getGraphiqueRecoltes(): array
    {
        $stats = VStatsMensuelles::recoltes()
            ->orderBy('periode')
            ->get();

        return [
            'labels' => $stats->pluck('periode')->toArray(),
            'data' => $stats->pluck('quantite_totale')->toArray(),
        ];
    }

    /**
     * Données pour graphique des ventes
     */
    public function getGraphiqueVentes(): array
    {
        $stats = VStatsMensuelles::ventes()
            ->orderBy('periode')
            ->get();

        return [
            'labels' => $stats->pluck('periode')->toArray(),
            'quantites' => $stats->pluck('quantite_totale')->toArray(),
            'montants' => $stats->pluck('montant_total')->toArray(),
        ];
    }

    /**
     * Données pour graphique des pertes
     */
    public function getGraphiquePertes(): array
    {
        $stats = VStatsMensuelles::pertes()
            ->orderBy('periode')
            ->get();

        return [
            'labels' => $stats->pluck('periode')->toArray(),
            'quantites' => $stats->pluck('quantite_totale')->toArray(),
            'montants' => $stats->pluck('montant_total')->toArray(),
        ];
    }

    /**
     * Répartition du stock par produit
     */
    public function getRepartitionStockParProduit(): array
    {
        $stocks = VStockActuel::selectRaw('nom_produit, SUM(stock_disponible) as total_stock, SUM(valeur_stock) as total_valeur')
            ->groupBy('nom_produit')
            ->orderBy('total_valeur', 'desc')
            ->get();

        return [
            'labels' => $stocks->pluck('nom_produit')->toArray(),
            'stocks' => $stocks->pluck('total_stock')->toArray(),
            'valeurs' => $stocks->pluck('total_valeur')->toArray(),
        ];
    }
}