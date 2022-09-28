<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class SucursalBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_sucursales';


    /**
     * Get the comprobantes for flashsucursale.
     */
    public function comprobantes()
    {
        return $this->hasMany(\Comprobante::class, 'sucursal_id', 'id');
    }

    /**
     * Get the piezas for flashsucursale.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'sucursal_id', 'id');
    }

    /**
     * Get the despachos for flashsucursale.
     */
    public function despachosOrigen()
    {
        return $this->hasMany(\Despacho::class, 'origen_id', 'id');
    }

    /**
     * Get the despachos for flashsucursale.
     */
    public function despachosDestino()
    {
        return $this->hasMany(\Despacho::class, 'destino_id', 'id');
    }

    /**
     * Get the talonarioResponsables for flashsucursale.
     */
    public function talonarioResponsables()
    {
        return $this->hasMany(\TalonarioResponsable::class, 'sucursal_id', 'id');
    }

    /**
     * Get the carteros for flashsucursale.
     */
    public function carteros()
    {
        return $this->hasMany(\Cartero::class, 'sucursal_id', 'id');
    }

    /**
     * Get the zonas for flashsucursale.
     */
    public function zonas()
    {
        return $this->hasMany(\Zona::class, 'sucursal_id', 'id');
    }

    /**
     * Get the usuarios for flashsucursale.
     */
    public function usuarios()
    {
        return $this->hasMany(\Usuario::class, 'sucursal_id', 'id');
    }


}
