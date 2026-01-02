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

    /**
     * Nom de la séquence Oracle
     */
    public function getSequenceName(): string
    {
        return 'seq_produit';
    }

    // ========================
    // RELATIONS
    // ========================

    public function varietes(): HasMany
    {
        return $this->hasMany(Variete::class, 'id_produit', 'id_produit');
    }

    // ========================
    // ACCESSEURS
    // ========================

    public function getNombreVarietesAttribute(): int
    {
        return $this->varietes()->count();
    }

    public function getStockTotalAttribute(): float
    {
        return $this->varietes->sum(function ($variete) {
            return $variete->stock_disponible;
        });
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeRecherche($query, string $terme)
    {
        return $query->where('nom_produit', 'LIKE', '%' . strtoupper($terme) . '%');
    }
}