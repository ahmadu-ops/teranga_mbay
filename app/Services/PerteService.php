<?php

namespace App\Services;

use App\Models\Perte;
use App\Models\Variete;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PerteService
{
    /**
     * Liste paginée des pertes
     */
    public function paginate(
        int $perPage = 15,
        ?int $varieteId = null,
        ?string $dateDebut = null,
        ?string $dateFin = null,
        ?string $motif = null
    ): LengthAwarePaginator {
        $query = Perte::with('variete.produit');

        if ($varieteId) {
            $query->deLaVariete($varieteId);
        }

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        if ($motif) {
            $query->parMotif($motif);
        }

        return $query->recent()->paginate($perPage);
    }

    /**
     * Trouver une perte
     */
    public function find(int $id): ?Perte
    {
        return Perte::with('variete.produit')->find($id);
    }

    /**
     * Créer une perte (le trigger calcule la valeur)
     */
    public function create(array $data): Perte
    {
        // Vérifier le stock avant
        $variete = Variete::find($data['id_variete']);
        if (!$variete->aStockSuffisant($data['quantite_perdue'])) {
            throw new \Exception("Stock insuffisant pour enregistrer cette perte. Disponible: {$variete->stock_disponible} kg");
        }

        return Perte::create([
            'date_perte' => $data['date_perte'],
            'quantite_perdue' => $data['quantite_perdue'],
            'motif_perte' => $data['motif_perte'],
            'id_variete' => $data['id_variete'],
        ])->load('variete.produit');
    }

    /**
     * Mettre à jour une perte
     */
    public function update(Perte $perte, array $data): Perte
    {
        $perte->update([
            'date_perte' => $data['date_perte'],
            'quantite_perdue' => $data['quantite_perdue'],
            'motif_perte' => $data['motif_perte'],
            'id_variete' => $data['id_variete'],
        ]);

        return $perte->fresh('variete.produit');
    }

    /**
     * Supprimer une perte
     */
    public function delete(Perte $perte): bool
    {
        return $perte->delete();
    }

    /**
     * Statistiques des pertes
     */
    public function getStats(?string $dateDebut = null, ?string $dateFin = null): array
    {
        $query = Perte::query();

        if ($dateDebut && $dateFin) {
            $query->periode($dateDebut, $dateFin);
        }

        return [
            'total_kg' => $query->sum('quantite_perdue'),
            'valeur_totale' => $query->sum('valeur_estimee'),
            'nombre_pertes' => $query->count(),
        ];
    }

    /**
     * Statistiques par motif
     */
    public function getStatsParMotif(): Collection
    {
        return Perte::selectRaw('motif_perte, COUNT(*) as nombre, SUM(quantite_perdue) as total_kg, SUM(valeur_estimee) as valeur_totale')
            ->groupBy('motif_perte')
            ->orderBy('valeur_totale', 'desc')
            ->get();
    }

    /**
     * Liste des motifs disponibles
     */
    public function getMotifs(): array
    {
        return Perte::MOTIFS;
    }
}