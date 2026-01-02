<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Variete extends Model
{
    use OracleModel;

    protected $table = 'variete';
    protected $primaryKey = 'id_variete';

    protected $fillable = [
        'nom_variete',
        'description',
        'id_produit',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSequenceName(): string
    {
        return 'seq_variete';
    }

    // ========================
    // RELATIONS
    // ========================

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_produit', 'id_produit');
    }

    public function prix(): HasMany
    {
        return $this->hasMany(Prix::class, 'id_variete', 'id_variete');
    }

    public function prixActuel(): HasOne
    {
        return $this->hasOne(Prix::class, 'id_variete', 'id_variete')
            ->whereNull('date_fin');
    }

    public function recoltes(): HasMany
    {
        return $this->hasMany(Recolte::class, 'id_variete', 'id_variete');
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class, 'id_variete', 'id_variete');
    }

    public function pertes(): HasMany
    {
        return $this->hasMany(Perte::class, 'id_variete', 'id_variete');
    }

    // ========================
    // ACCESSEURS
    // ========================

    public function getNomCompletAttribute(): string
    {
        return $this->produit->nom_produit . ' - ' . $this->nom_variete;
    }

    public function getPrixActuelValueAttribute(): ?float
    {
        return $this->prixActuel?->prix_kg;
    }

    public function getTotalRecolteAttribute(): float
    {
        return $this->recoltes()->sum('quantite_recolte') ?? 0;
    }

    public function getTotalVenduAttribute(): float
    {
        return $this->ventes()->sum('quantite_vendu') ?? 0;
    }

    public function getTotalPerduAttribute(): float
    {
        return $this->pertes()->sum('quantite_perdue') ?? 0;
    }

    public function getStockDisponibleAttribute(): float
    {
        return $this->total_recolte - $this->total_vendu - $this->total_perdu;
    }

    public function getValeurStockAttribute(): float
    {
        return $this->stock_disponible * ($this->prix_actuel_value ?? 0);
    }

    public function getChiffreAffairesAttribute(): float
    {
        return $this->ventes()->sum('montant_total') ?? 0;
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeAvecStock($query)
    {
        return $query->whereIn('id_variete', function ($subquery) {
            $subquery->select('id_variete')
                ->from('v_stock_actuel')
                ->where('stock_disponible', '>', 0);
        });
    }

    public function scopeDuProduit($query, int $idProduit)
    {
        return $query->where('id_produit', $idProduit);
    }

    public function scopeRecherche($query, string $terme)
    {
        return $query->where('nom_variete', 'LIKE', '%' . strtoupper($terme) . '%');
    }

    // ========================
    // MÉTHODES
    // ========================

    public function aStockSuffisant(float $quantite): bool
    {
        return $this->stock_disponible >= $quantite;
    }
}