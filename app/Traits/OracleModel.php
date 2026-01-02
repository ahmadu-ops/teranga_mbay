<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait OracleModel
{
    /**
     * Désactiver l'auto-increment Laravel (géré par séquence Oracle)
     */
    public function initializeOracleModel()
    {
        $this->incrementing = false;
    }

    /**
     * Le type de la clé primaire
     */
    protected $keyType = 'int';

    /**
     * Format des dates pour Oracle
     */
    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Boot du trait - Récupère l'ID de la séquence avant insertion
     */
    protected static function bootOracleModel(): void
    {
        static::creating(function ($model) {
            $primaryKey = $model->getKeyName();
            
            // Si l'ID n'est pas déjà défini, récupérer depuis la séquence
            if (empty($model->{$primaryKey})) {
                $sequenceName = $model->getSequenceName();
                $result = DB::selectOne("SELECT {$sequenceName}.NEXTVAL as id FROM DUAL");
                $model->{$primaryKey} = $result->id;
            }
        });
    }

    /**
     * Récupérer le nom de la séquence pour ce modèle
     */
    public function getSequenceName(): string
    {
        // Par défaut : seq_ + nom de la table (sans 's' final si pluriel)
        $table = $this->getTable();
        return 'seq_' . rtrim($table, 's');
    }
}