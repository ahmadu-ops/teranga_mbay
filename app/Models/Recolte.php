<?php

namespace App\Models;

use App\Traits\OracleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recolte extends Model
{
    use OracleModel;

    protected $table = 'recolte';
    protected $primaryKey = 'id_recolte';

    protected $fillable = [
        'date_recolte',
        'quantite_recolte',
        'id_variete',
    ];

    protected $casts = [
        'quantite_recolte' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSequenceName(): string
    {
        return 'seq_recolte';
    }

    // ========================
    // MUTATEURS
    // ========================

    public function setDateRecolteAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['date_recolte'] = $value->format('Y-m-d');
        } elseif ($value) {
            $this->attributes['date_recolte'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['date_recolte'] = null;
        }
    }

    public function getDateRecolteAttribute($value)
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
    // ACCESSEURS
    // ========================

    public function getQuantiteFormateeAttribute(): string
    {
        return number_format($this->quantite_recolte, 2, ',', ' ') . ' kg';
    }

    public function getValeurEstimeeAttribute(): float
    {
        $prixActuel = $this->variete->prix_actuel_value ?? 0;
        return $this->quantite_recolte * $prixActuel;
    }

    public function getValeurFormateeAttribute(): string
    {
        return number_format($this->valeur_estimee, 0, ',', ' ') . ' FCFA';
    }

    // ========================
    // SCOPES
    // ========================

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_recolte', [$dateDebut, $dateFin]);
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereRaw("TRUNC(date_recolte) = TRUNC(SYSDATE)");
    }

    public function scopeCetteSemaine($query)
    {
        return $query->whereRaw("date_recolte >= TRUNC(SYSDATE, 'IW') AND date_recolte < TRUNC(SYSDATE, 'IW') + 7");
    }

    public function scopeCeMois($query)
    {
        return $query->whereRaw("TO_CHAR(date_recolte, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')");
    }

    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_recolte', 'desc');
    }
}