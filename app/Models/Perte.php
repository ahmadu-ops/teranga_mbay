<?php

namespace App\Models;

use App\Traits\OracleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Perte extends Model
{
    use OracleModel;

    protected $table = 'perte';
    protected $primaryKey = 'id_perte';

    public const MOTIFS = [
        'Pourriture',
        'Vol',
        'Accident',
        'Invendu',
        'Autre',
    ];

    protected $fillable = [
        'date_perte',
        'quantite_perdue',
        'motif_perte',
        'id_variete',
    ];

    protected $casts = [
        'quantite_perdue' => 'decimal:2',
        'valeur_estimee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSequenceName(): string
    {
        return 'seq_perte';
    }

    // ========================
    // MUTATEURS - Format date pour Oracle
    // ========================

    public function setDatePerteAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['date_perte'] = $value->format('Y-m-d');
        } elseif ($value) {
            $this->attributes['date_perte'] = Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['date_perte'] = null;
        }
    }

    public function getDatePerteAttribute($value)
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
        return number_format($this->quantite_perdue, 2, ',', ' ') . ' kg';
    }

    public function getValeurFormateeAttribute(): string
    {
        return number_format($this->valeur_estimee, 0, ',', ' ') . ' FCFA';
    }

    public function getMotifIconeAttribute(): string
    {
        return match ($this->motif_perte) {
            'Pourriture' => '🦠',
            'Vol' => '🦹',
            'Accident' => '💥',
            'Invendu' => '📦',
            'Autre' => '❓',
            default => '❓',
        };
    }

    // ========================
    // SCOPES
    // ========================

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        if ($dateDebut && $dateFin) {
            return $query->whereRaw("date_perte BETWEEN TO_DATE(?, 'YYYY-MM-DD') AND TO_DATE(?, 'YYYY-MM-DD')", [$dateDebut, $dateFin]);
        }
        return $query;
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereRaw("TRUNC(date_perte) = TRUNC(SYSDATE)");
    }

    public function scopeCeMois($query)
    {
        return $query->whereRaw("TO_CHAR(date_perte, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')");
    }

    public function scopeParMotif($query, string $motif)
    {
        return $query->where('motif_perte', $motif);
    }

    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_perte', 'desc');
    }
}