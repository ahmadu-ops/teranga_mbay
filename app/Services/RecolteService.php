<?php

namespace App\Services;

use App\Models\Recolte;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RecolteService
{
    /**
     * Liste paginée des récoltes
     */
    public function paginate(
        int $perPage = 15,
        ?int $varieteId = null,
        ?string $dateDebut = null,
        ?string $dateFin = null
    ): LengthAwarePaginator {
        $query = Recolte::with('variete.produit');

        if ($varieteId) {
            $query->deLaVariete($varieteId);
        }

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        return $query->recent()->paginate($perPage);
    }

    /**
     * Récoltes du jour
     */
    public function aujourdhui(): Collection
    {
        return Recolte::with('variete.produit')
            ->aujourdhui()
            ->recent()
            ->get();
    }

    /**
     * Récoltes de la semaine
     */
    public function cetteSemaine(): Collection
    {
        return Recolte::with('variete.produit')
            ->cetteSemaine()
            ->recent()
            ->get();
    }

    /**
     * Trouver une récolte
     */
    public function find(int $id): ?Recolte
    {
        return Recolte::with('variete.produit')->find($id);
    }

    /**
     * Créer une récolte
     */
    public function create(array $data): Recolte
    {
        return Recolte::create([
            'date_recolte' => $data['date_recolte'],
            'quantite_recolte' => $data['quantite_recolte'],
            'id_variete' => $data['id_variete'],
        ])->load('variete.produit');
    }

    /**
     * Mettre à jour une récolte
     */
    public function update(Recolte $recolte, array $data): Recolte
    {
        $recolte->update([
            'date_recolte' => $data['date_recolte'],
            'quantite_recolte' => $data['quantite_recolte'],
            'id_variete' => $data['id_variete'],
        ]);

        return $recolte->fresh('variete.produit');
    }

    /**
     * Supprimer une récolte
     */
    public function delete(Recolte $recolte): bool
    {
        return $recolte->delete();
    }

    /**
     * Statistiques des récoltes
     */
    public function getStats(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $query = Recolte::query();

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        return [
            'total_kg' => $query->sum('quantite_recolte'),
            'nombre_recoltes' => $query->count(),
            'moyenne_kg' => $query->avg('quantite_recolte'),
        ];
    }
}