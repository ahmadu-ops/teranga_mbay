<?php

namespace App\Models;

use App\Traits\OracleModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    use OracleModel;

    protected $table = 'vente';
    protected $primaryKey = 'id_vente';

    protected $fillable = [
        'date_vente',
        'quantite_vendu',
        'nom_client',
        'id_variete',
    ];

    protected $casts = [
        'quantite_vendu' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSequenceName(): string
    {
        return 'seq_vente';
    }

    // ========================
    // MUTATEURS
    // ========================

    public function setDateVenteAttribute($value)
    {
        if ($value) {
            $this->attributes['date_vente'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getDateVenteAttribute($value)
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
        return number_format($this->quantite_vendu, 2, ',', ' ') . ' kg';
    }

    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant_total, 0, ',', ' ') . ' FCFA';
    }

    public function getPrixUnitaireFormateAttribute(): string
    {
        return number_format($this->prix_unitaire, 0, ',', ' ') . ' FCFA/kg';
    }

    public function getClientAttribute(): string
    {
        return $this->nom_client ?? 'Client anonyme';
    }

    // ========================
    // SCOPES
    // ========================

    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_vente', [$dateDebut, $dateFin]);
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereRaw("TRUNC(date_vente) = TRUNC(SYSDATE)");
    }

    public function scopeCetteSemaine($query)
    {
        return $query->whereRaw("date_vente >= TRUNC(SYSDATE, 'IW') AND date_vente < TRUNC(SYSDATE, 'IW') + 7");
    }

    public function scopeCeMois($query)
    {
        return $query->whereRaw("TO_CHAR(date_vente, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')");
    }

    public function scopeDeLaVariete($query, int $idVariete)
    {
        return $query->where('id_variete', $idVariete);
    }

    public function scopeDuClient($query, string $nomClient)
    {
        return $query->where('nom_client', 'LIKE', '%' . strtoupper($nomClient) . '%');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('date_vente', 'desc');
    }
}