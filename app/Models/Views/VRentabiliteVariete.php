<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VRentabiliteVariete extends Model
{
    protected $table = 'v_rentabilite_variete';
    public $timestamps = false;
    protected $primaryKey = 'id_variete';

    protected $casts = [
        'quantite_recoltee' => 'decimal:2',
        'quantite_vendue' => 'decimal:2',
        'revenus_ventes' => 'decimal:2',
        'quantite_perdue' => 'decimal:2',
        'valeur_pertes' => 'decimal:2',
        'benefice_net' => 'decimal:2',
        'taux_vente_pct' => 'decimal:2',
        'taux_perte_pct' => 'decimal:2',
    ];

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

    public function scopeRentable($query)
    {
        return $query->where('benefice_net', '>', 0);
    }

    public function scopeEnPerte($query)
    {
        return $query->where('benefice_net', '<', 0);
    }

    public function scopeTopVentes($query, int $limit = 10)
    {
        return $query->orderBy('revenus_ventes', 'desc')->limit($limit);
    }

    public function scopeTopPertes($query, int $limit = 10)
    {
        return $query->orderBy('valeur_pertes', 'desc')->limit($limit);
    }
}