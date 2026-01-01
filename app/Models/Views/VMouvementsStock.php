<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VMouvementsStock extends Model
{
    protected $table = 'v_mouvements_stock';
    public $timestamps = false;

    protected $casts = [
        'date_mouvement' => 'date',
        'quantite' => 'decimal:2',
        'montant' => 'decimal:2',
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

    public function scopeRecoltes($query)
    {
        return $query->where('type_mouvement', 'RECOLTE');
    }

    public function scopeVentes($query)
    {
        return $query->where('type_mouvement', 'VENTE');
    }

    public function scopePertes($query)
    {
        return $query->where('type_mouvement', 'PERTE');
    }

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_mouvement', 'desc');
    }
}