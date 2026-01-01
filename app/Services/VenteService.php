<?php

namespace App\Services;

use App\Models\Vente;
use App\Models\Variete;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VenteService
{
    /**
     * Liste paginée des ventes
     */
    public function paginate(
        int $perPage = 15,
        ?int $varieteId = null,
        ?string $dateDebut = null,
        ?string $dateFin = null,
        ?string $client = null
    ): LengthAwarePaginator {
        $query = Vente::with('variete.produit');

        if ($varieteId) {
            $query->deLaVariete($varieteId);
        }

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        if ($client) {
            $query->duClient($client);
        }

        return $query->recent()->paginate($perPage);
    }

    /**
     * Ventes du jour
     */
    public function aujourdhui(): Collection
    {
        return Vente::with('variete.produit')
            ->aujourdhui()
            ->recent()
            ->get();
    }

    /**
     * Trouver une vente
     */
    public function find(int $id): ?Vente
    {
        return Vente::with('variete.produit')->find($id);
    }

    /**
     * Créer une vente (le trigger calcule le montant)
     */
    public function create(array $data): Vente
    {
        // Vérifier le stock avant (même si le trigger le fait aussi)
        $variete = Variete::find($data['id_variete']);
        if (!$variete->aStockSuffisant($data['quantite_vendu'])) {
            throw new \Exception("Stock insuffisant. Disponible: {$variete->stock_disponible} kg");
        }

        return Vente::create([
            'date_vente' => $data['date_vente'],
            'quantite_vendu' => $data['quantite_vendu'],
            'nom_client' => $data['nom_client'] ?? null,
            'id_variete' => $data['id_variete'],
        ])->load('variete.produit');
    }

    /**
     * Mettre à jour une vente
     */
    public function update(Vente $vente, array $data): Vente
    {
        $vente->update([
            'date_vente' => $data['date_vente'],
            'quantite_vendu' => $data['quantite_vendu'],
            'nom_client' => $data['nom_client'] ?? null,
            'id_variete' => $data['id_variete'],
        ]);

        return $vente->fresh('variete.produit');
    }

    /**
     * Supprimer une vente
     */
    public function delete(Vente $vente): bool
    {
        return $vente->delete();
    }

    /**
     * Statistiques des ventes
     */
    public function getStats(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $query = Vente::query();

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        return [
            'total_kg' => $query->sum('quantite_vendu'),
            'chiffre_affaires' => $query->sum('montant_total'),
            'nombre_ventes' => $query->count(),
            'panier_moyen' => $query->avg('montant_total'),
        ];
    }

    /**
     * Liste des clients uniques
     */
    public function getClients(): Collection
    {
        return Vente::select('nom_client')
            ->whereNotNull('nom_client')
            ->distinct()
            ->orderBy('nom_client')
            ->pluck('nom_client');
    }
}