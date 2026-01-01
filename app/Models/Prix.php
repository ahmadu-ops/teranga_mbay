<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prix extends Model
{
    use OracleModel;

    protected $table = 'prix';
    protected $primaryKey = 'id_prix';

    protected $fillable = [
        'prix_kg',
        'date_debut',
        'date_fin',
        'id_variete',
    ];

    protected $casts = [
        'prix_kg' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    /**
     * Un prix appartient à une variété
     */
    public function variete(): BelongsTo
    {
        return $this->belongsTo(Variete::class, 'id_variete', 'id_variete');
    }

    // ========================
    // ACCESSEURS
    // ========================

    /**
     * Prix formaté
     */
    public function getPrixFormateAttribute(): string
    {
        return number_format($this->prix_kg, 0, ',', ' ') . ' FCFA/kg';
    }

    /**
     * Vérifie si le prix est actif
     */
    public function getEstActifAttribute(): bool
    {
        return is_null($this->date_fin);
    }

    /**
     * Période du prix
     */
    public function getPeriodeAttribute(): string
    {
        $debut = $this->date_debut->format('d/m/Y');
        $fin = $this->date_fin ? $this->date_fin->format('d/m/Y') : 'En cours';
        return "{$debut} → {$fin}";
    }

    // ========================
    // SCOPES
    // ========================

    /**
     * Prix actifs uniquement
     */
    public function scopeActif($query)
    {
        return $query->whereNull('date_fin');
    }

    /**
     * Historique des prix
     */
    public function scopeHistorique($query)
    {
        return $query->whereNotNull('date_fin')->orderBy('date_fin', 'desc');
    }

    /**
     * Prix d'une variété
     */
    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }
}