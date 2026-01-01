<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    use OracleModel;

    protected $table = 'vente';
    protected $primaryKey = 'id_vente';

    protected $fillable = [
        'date_vente',
        'quantite_vendu',
        'nom_client',
        'id_variete',
        // prix_unitaire et montant_total sont calculés par trigger
    ];

    protected $casts = [
        'date_vente' => 'date',
        'quantite_vendu' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    /**
     * Une vente appartient à une variété
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
        return number_format($this->quantite_vendu, 2, ',', ' ') . ' kg';
    }

    /**
     * Montant formaté
     */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Prix unitaire formaté
     */
    public function getPrixUnitaireFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 0, ',', ' ') . ' FCFA/kg';
    }

    /**
     * Nom du client (ou anonyme)
     */
    public function getClientAttribute(): string
    {
        return $this->nom_client ?? 'Client anonyme';
    }

    // ========================
    // SCOPES
    // ========================

    /**
     * Ventes d'une période
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }

    /**
     * Ventes du jour
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_vente', now());
    }

    /**
     * Ventes de la semaine
     */
    public function scopeCetteSemaine($query)
    {
        return $query->whereBetween('date_vente', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Ventes du mois
     */
    public function scopeCeMois($query)
    {
        return $query->whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year);
    }

    /**
     * Ventes d'une variété
     */
    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }

    /**
     * Ventes d'un client
     */
    public function scopeDuClient($query, string $nomClient)
    {
        return $query->where('nom_client', 'LIKE', '%' . strtoupper($nomClient) . '%');
    }

    /**
     * Tri par date décroissante
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('date_vente', 'desc');
    }
}