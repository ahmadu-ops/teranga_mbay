<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VDashboardResume extends Model
{
    protected $table = 'v_dashboard_resume';
    public $timestamps = false;

    protected $casts = [
        'nb_produits' => 'integer',
        'nb_varietes' => 'integer',
        'total_recolte_kg' => 'decimal:2',
        'total_ventes_kg' => 'decimal:2',
        'chiffre_affaires' => 'decimal:2',
        'total_pertes_kg' => 'decimal:2',
        'valeur_pertes' => 'decimal:2',
        'stock_total_kg' => 'decimal:2',
        'valeur_stock_total' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }

    /**
     * Récupérer les données du dashboard
     */
    public static function getData(): ?self
    {
        return self::first();
    }
}