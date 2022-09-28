<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ZonaBase extends Model {


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_sucursales_zonas';


    /**
     * Get the sucursale record associated with the flashsucursaleszona.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the hojas for flashsucursaleszona.
     */
    public function hojas()
    {
        return $this->hasMany(\Hoja::class, 'zona_id', 'id');
    }


}
