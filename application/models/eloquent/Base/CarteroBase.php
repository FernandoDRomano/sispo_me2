<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class CarteroBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_sucursales_carteros';


    /**
     * Get the sucursale record associated with the flashsucursalescartero.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the hojas for flashsucursalescartero.
     */
    public function hojas()
    {
        return $this->hasMany(\Hoja::class, 'cartero_id', 'id');
    }


}
