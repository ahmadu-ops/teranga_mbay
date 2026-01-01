<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VStockActuel extends Model
{
    protected $table = 'v_stock_actuel';
    public $timestamps = false;
    protected $primaryKey = 'id_variete';

    protected $casts = [
        'total_recolte' => 'decimal:2',
        'total_vendu' => 'decimal:2',
        'total_perdu' => 'decimal:2',
        'stock_disponible' => 'decimal:2',
        'prix_actuel' => 'decimal:2',
        'valeur_stock' => 'decimal:2',
    ];

    // Vue en lecture seule
    public static function boot()
    {
        parent::boot();
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeAvecStock($query)
    {
        return $query->where('stock_disponible', '>', 0);
    }

    public function scopeSansStock($query)
    {
        return $query->where('stock_disponible', '<=', 0);
    }

    public function scopeDuProduit($query, int $idProduit)
    {
        return $query->where('id_produit', $idProduit);
    }
}