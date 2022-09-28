<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class TransporteBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_transportes';


    /**
     * Get the hojas for flashtransporte.
     */
    public function hojas()
    {
        return $this->hasMany(\Hoja::class, 'transporte_id', 'id');
    }

    /**
     * Get the despachos for flashtransporte.
     */
    public function despachos()
    {
        return $this->hasMany(\Despacho::class, 'transporte_id', 'id');
    }


}
