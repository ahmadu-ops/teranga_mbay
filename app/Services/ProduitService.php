<?php

namespace App\Services;

use App\Models\Produit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProduitService
{
    /**
     * Liste paginée des produits
     */
    public function paginate(int $perPage = 15, ?string $recherche = null): LengthAwarePaginator
    {
        $query = Produit::with('varietes');

        if ($recherche) {
            $query->recherche($recherche);
        }

        return $query->orderBy('nom_produit')->paginate($perPage);
    }

    /**
     * Tous les produits (pour les selects)
     */
    public function all(): Collection
    {
        return Produit::orderBy('nom_produit')->get();
    }

    /**
     * Trouver un produit par ID
     */
    public function find(int $id): ?Produit
    {
        return Produit::with('varietes.prixActuel')->find($id);
    }

    /**
     * Créer un produit
     */
    public function create(array $data): Produit
    {
        return Produit::create($data);
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Produit $produit, array $data): Produit
    {
        $produit->update($data);
        return $produit->fresh();
    }

    /**
     * Supprimer un produit
     */
    public function delete(Produit $produit): bool
    {
        return $produit->delete();
    }

    /**
     * Statistiques d'un produit
     */
    public function getStats(Produit $produit): array
    {
        $varietes = $produit->varietes;

        return [
            'nombre_varietes' => $varietes->count(),
            'total_recolte' => $varietes->sum(fn($v) => $v->total_recolte),
            'total_vendu' => $varietes->sum(fn($v) => $v->total_vendu),
            'total_perdu' => $varietes->sum(fn($v) => $v->total_perdu),
            'stock_total' => $varietes->sum(fn($v) => $v->stock_disponible),
            'chiffre_affaires' => $varietes->sum(fn($v) => $v->chiffre_affaires),
        ];
    }
}