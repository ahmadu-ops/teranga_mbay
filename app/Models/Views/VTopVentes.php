<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Model;

class VTopVentes extends Model
{
    protected $table = 'v_top_ventes';
    public $timestamps = false;

    protected $casts = [
        'total_vendu_kg' => 'decimal:2',
        'total_revenus' => 'decimal:2',
        'nb_ventes' => 'integer',
        'prix_moyen' => 'decimal:2',
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

    public function scopeTop($query, int $limit = 10)
    {
        return $query->orderBy('total_revenus', 'desc')->limit($limit);
    }
}