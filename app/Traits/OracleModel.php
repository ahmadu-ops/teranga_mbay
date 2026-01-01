<?php

namespace App\Traits;

trait OracleModel
{
    /**
     * Initialiser le trait pour le modèle.
     * Cette méthode est appelée automatiquement par le constructeur du Model Eloquent.
     * Elle permet de surcharger $incrementing sans conflit de définition PHP.
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
     * (Doit rester public comme vu précédemment)
     */
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Les colonnes de timestamp
     */
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}