<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VStatsMensuelles extends Model
{
    protected $table = 'v_stats_mensuelles';
    public $timestamps = false;

    protected $casts = [
        'nb_operations' => 'integer',
        'quantite_totale' => 'decimal:2',
        'montant_total' => 'decimal:2',
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
        return $query->where('type_operation', 'RECOLTE');
    }

    public function scopeVentes($query)
    {
        return $query->where('type_operation', 'VENTE');
    }

    public function scopePertes($query)
    {
        return $query->where('type_operation', 'PERTE');
    }

    public function scopePeriode($query, string $periode)
    {
        return $query->where('periode', $periode);
    }
}