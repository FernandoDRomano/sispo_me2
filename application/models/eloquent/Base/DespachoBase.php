<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class DespachoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_despacho';


    /**
     * Get the sucursale record associated with the flashpiezasdespacho.
     */
    public function sucursalOrigen()
    {
        return $this->belongsTo(\Sucursal::class, 'origen_id', 'id');
    }

    /**
     * Get the sucursale record associated with the flashpiezasdespacho.
     */
    public function sucursalDestino()
    {
        return $this->belongsTo(\Sucursal::class, 'destino_id', 'id');
    }

    /**
     * Get the transporte record associated with the flashpiezasdespacho.
     */
    public function transporte()
    {
        return $this->belongsTo(\Transporte::class, 'transporte_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashpiezasdespacho.
     */
    public function usuarioOrigen()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_origen_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashpiezasdespacho.
     */
    public function usuarioDestino()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_destino_id', 'id');
    }

    /**
     * The piezas that belong to the flashpiezasdespacho.
     */
    public function piezas()
    {
        return $this->belongsToMany(\Pieza::class, 'flash_piezas_despacho_piezas', 'despacho_id', 'pieza_id');
    }

    /**
     * Get the despachoPiezas for flashpiezasdespacho.
     */
    public function despachoPiezas()
    {
        return $this->hasMany(\DespachoPieza::class, 'despacho_id', 'id');
    }


}
