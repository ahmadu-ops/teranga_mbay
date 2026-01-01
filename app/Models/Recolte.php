<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recolte extends Model
{
    use OracleModel;

    protected $table = 'recolte';
    protected $primaryKey = 'id_recolte';

    protected $fillable = [
        'date_recolte',
        'quantite_recolte',
        'id_variete',
    ];

    protected $casts = [
        'date_recolte' => 'date',
        'quantite_recolte' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    /**
     * Une récolte appartient à une variété
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
        return number_format($this->quantite_recolte, 2, ',', ' ') . ' kg';
    }

    /**
     * Valeur estimée de la récolte
     */
    public function getValeurEstimeeAttribute(): float
    {
        $prixActuel = $this->variete->prix_actuel_value ?? 0;
        return $this->quantite_recolte * $prixActuel;
    }

    /**
     * Valeur formatée
     */
    public function getValeurFormateeAttribute(): string
    {
        return number_format($this->valeur_estimee, 0, ',', ' ') . ' FCFA';
    }

    // ========================
    // SCOPES
    // ========================

    /**
     * Récoltes d'une période
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_recolte', [$dateDebut, $dateFin]);
    }

    /**
     * Récoltes du jour
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_recolte', now());
    }

    /**
     * Récoltes de la semaine
     */
    public function scopeCetteSemaine($query)
    {
        return $query->whereBetween('date_recolte', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Récoltes du mois
     */
    public function scopeCeMois($query)
    {
        return $query->whereMonth('date_recolte', now()->month)
            ->whereYear('date_recolte', now()->year);
    }

    /**
     * Récoltes d'une variété
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
        return $query->orderBy('date_recolte', 'desc');
    }
}