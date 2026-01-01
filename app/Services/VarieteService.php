<?php

namespace App\Services;

use App\Models\Variete;
use App\Models\Prix;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class VarieteService
{
    /**
     * Liste paginée des variétés
     */
    public function paginate(int $perPage = 15, ?int $produitId = null, ?string $recherche = null): LengthAwarePaginator
    {
        $query = Variete::with(['produit', 'prixActuel']);

        if ($produitId) {
            $query->duProduit($produitId);
        }

        if ($recherche) {
            $query->recherche($recherche);
        }

        return $query->orderBy('nom_variete')->paginate($perPage);
    }

    /**
     * Toutes les variétés (pour les selects)
     */
    public function all(?int $produitId = null): Collection
    {
        $query = Variete::with(['produit', 'prixActuel']);

        if ($produitId) {
            $query->duProduit($produitId);
        }

        return $query->orderBy('nom_variete')->get();
    }

    /**
     * Variétés avec stock disponible
     */
    public function avecStock(): Collection
    {
        return Variete::with(['produit', 'prixActuel'])
            ->avecStock()
            ->orderBy('nom_variete')
            ->get();
    }

    /**
     * Trouver une variété par ID
     */
    public function find(int $id): ?Variete
    {
        return Variete::with(['produit', 'prixActuel', 'prix'])->find($id);
    }

    /**
     * Créer une variété avec son prix initial
     */
    public function create(array $data): Variete
    {
        $variete = Variete::create([
            'nom_variete' => $data['nom_variete'],
            'description' => $data['description'] ?? null,
            'id_produit' => $data['id_produit'],
        ]);

        // Créer le prix initial si fourni
        if (isset($data['prix_kg']) && $data['prix_kg'] > 0) {
            Prix::create([
                'prix_kg' => $data['prix_kg'],
                'date_debut' => $data['date_debut'] ?? now(),
                'id_variete' => $variete->id_variete,
            ]);
        }

        return $variete->load(['produit', 'prixActuel']);
    }

    /**
     * Mettre à jour une variété
     */
    public function update(Variete $variete, array $data): Variete
    {
        $variete->update([
            'nom_variete' => $data['nom_variete'],
            'description' => $data['description'] ?? null,
            'id_produit' => $data['id_produit'],
        ]);

        return $variete->fresh(['produit', 'prixActuel']);
    }

    /**
     * Mettre à jour le prix d'une variété
     */
    public function updatePrix(Variete $variete, float $nouveauPrix, ?string $dateDebut = null): Prix
    {
        return Prix::create([
            'prix_kg' => $nouveauPrix,
            'date_debut' => $dateDebut ?? now(),
            'id_variete' => $variete->id_variete,
        ]);
    }

    /**
     * Supprimer une variété
     */
    public function delete(Variete $variete): bool
    {
        return $variete->delete();
    }

    /**
     * Détails complets d'une variété
     */
    public function getDetails(Variete $variete): array
    {
        return [
            'variete' => $variete,
            'produit' => $variete->produit,
            'prix_actuel' => $variete->prix_actuel_value,
            'historique_prix' => $variete->prix()->orderBy('date_debut', 'desc')->get(),
            'total_recolte' => $variete->total_recolte,
            'total_vendu' => $variete->total_vendu,
            'total_perdu' => $variete->total_perdu,
            'stock_disponible' => $variete->stock_disponible,
            'valeur_stock' => $variete->valeur_stock,
            'chiffre_affaires' => $variete->chiffre_affaires,
        ];
    }
}