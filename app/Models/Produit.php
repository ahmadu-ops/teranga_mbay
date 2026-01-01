<?php

namespace App\Models;

use App\Traits\OracleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use OracleModel;

    protected $table = 'produit';
    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'nom_produit',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========================
    // RELATIONS
    // ========================

    /**
     * Un produit a plusieurs variétés
     */
    public function varietes(): HasMany
    {
        return $this->hasMany(Variete::class, 'id_produit', 'id_produit');
    }

    // ========================
    // ACCESSEURS
    // ========================

    /**
     * Nombre de variétés
     */
    public function getNombreVarietesAttribute(): int
    {
        return $this->varietes()->count();
    }

    /**
     * Stock total du produit (toutes variétés confondues)
     */
    public function getStockTotalAttribute(): float
    {
        return $this->varietes->sum(function ($variete) {
            return $variete->stock_disponible;
        });
    }

    // ========================
    // SCOPES
    // ========================

    /**
     * Produits avec stock disponible
     */
    public function scopeAvecStock($query)
    {
        return $query->whereHas('varietes', function ($q) {
            $q->whereRaw('(
                SELECT NVL(SUM(r.quantite_recolte), 0) - NVL(SUM(v.quantite_vendu), 0) - NVL(SUM(p.quantite_perdue), 0)
                FROM recolte r
                LEFT JOIN vente v ON r.id_variete = v.id_variete
                LEFT JOIN perte p ON r.id_variete = p.id_variete
                WHERE r.id_variete = variete.id_variete
            ) > 0');
        });
    }

    /**
     * Recherche par nom
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('nom_produit', 'LIKE', '%' . strtoupper($terme) . '%');
    }
}