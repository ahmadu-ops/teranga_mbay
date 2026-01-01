<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perte extends Model
{
    use OracleModel;

    protected $table = 'perte';
    protected $primaryKey = 'id_perte';

    // Motifs autorisés
    public const MOTIFS = [
        'Pourriture',
        'Vol',
        'Accident',
        'Invendu',
        'Autre',
    ];

    protected $fillable = [
        'date_perte',
        'quantite_perdue',
        'motif_perte',
        'id_variete',
        // valeur_estimee est calculée par trigger
    ];

    protected $casts = [
        'date_perte' => 'date',
        'quantite_perdue' => 'decimal:2',
        'valeur_estimee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    /**
     * Une perte appartient à une variété
     */
    public function variete(): BelongsTo
    {
        return $this->belongsTo(Variete::class, 'id_variete', 'id_variete');
    }

    // ========================
    // ACCESSEURS
    // ========================

    /**
     * Quantité formatée
     */
    public function getQuantiteFormateeAttribute(): string
    {
        return number_format($this->quantite_perdue, 2, ',', ' ') . ' kg';
    }

    /**
     * Valeur formatée
     */
    public function getValeurFormateeAttribute(): string
    {
        return number_format($this->valeur_estimee, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Icône du motif
     */
    public function getMotifIconeAttribute(): string
    {
        return match ($this->motif_perte) {
            'Pourriture' => '🦠',
            'Vol' => '🦹',
            'Accident' => '💥',
            'Invendu' => '📦',
            'Autre' => '❓',
            default => '❓',
        };
    }

    // ========================
    // SCOPES
    // ========================

    /**
     * Pertes d'une période
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_perte', [$dateDebut, $dateFin]);
    }

    /**
     * Pertes du jour
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_perte', now());
    }

    /**
     * Pertes du mois
     */
    public function scopeCeMois($query)
    {
        return $query->whereMonth('date_perte', now()->month)
            ->whereYear('date_perte', now()->year);
    }

    /**
     * Pertes par motif
     */
    public function scopeParMotif($query, string $motif)
    {
        return $query->where('motif_perte', $motif);
    }

    /**
     * Pertes d'une variété
     */
    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }

    /**
     * Tri par date décroissante
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('date_perte', 'desc');
    }
}