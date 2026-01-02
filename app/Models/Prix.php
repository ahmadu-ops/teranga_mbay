<?php

namespace App\Models;

use App\Traits\OracleModel;
use Carbon\Carbon;
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSequenceName(): string
    {
        return 'seq_prix';
    }

    // ========================
    // MUTATEURS
    // ========================

    public function setDateDebutAttribute($value)
    {
        if ($value) {
            $this->attributes['date_debut'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function setDateFinAttribute($value)
    {
        if ($value) {
            $this->attributes['date_fin'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['date_fin'] = null;
        }
    }

    // ========================
    // ACCESSEURS
    // ========================

    public function getDateDebutAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getDateFinAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // ========================
    // RELATIONS
    // ========================

    public function variete(): BelongsTo
    {
        return $this->belongsTo(Variete::class, 'id_variete', 'id_variete');
    }

    // ========================
    // AUTRES ACCESSEURS
    // ========================

    public function getPrixFormateAttribute(): string
    {
        return number_format($this->prix_kg, 0, ',', ' ') . ' FCFA/kg';
    }

    public function getEstActifAttribute(): bool
    {
        return is_null($this->attributes['date_fin'] ?? null);
    }

    public function getPeriodeAttribute(): string
    {
        $debut = $this->date_debut ? $this->date_debut->format('d/m/Y') : '-';
        $fin = $this->date_fin ? $this->date_fin->format('d/m/Y') : 'En cours';
        return "{$debut} → {$fin}";
    }

    // ========================
    // SCOPES
    // ========================

    public function scopeActif($query)
    {
        return $query->whereNull('date_fin');
    }

    public function scopeHistorique($query)
    {
        return $query->whereNotNull('date_fin')->orderBy('date_fin', 'desc');
    }

    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }
}