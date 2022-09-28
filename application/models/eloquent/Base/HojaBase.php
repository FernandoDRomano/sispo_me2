<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class HojaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_hojas_rutas';

    /**
     * Get the distribuidore record associated with the flashhojasruta.
     */
    public function distribuidor()
    {
        return $this->belongsTo(\Distribuidor::class, 'distribuidor_id', 'id');
    }

    /**
     * Get the sucursal record associated with the flashhojasruta.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the cartero record associated with the flashhojasruta.
     */
    public function cartero()
    {
        return $this->belongsTo(\Cartero::class, 'cartero_id', 'id');
    }

    /**
     * Get the sucursalesZona record associated with the flashhojasruta.
     */
    public function zona()
    {
        return $this->belongsTo(\Zona::class, 'zona_id', 'id');
    }

    /**
     * Get the transporte record associated with the flashhojasruta.
     */
    public function transporte()
    {
        return $this->belongsTo(\Transporte::class, 'transporte_id', 'id');
    }

    /**
     * Get the subpiezas for flashhojasruta.
     */
    public function subpiezas()
    {
        return $this->hasMany(\Subpieza::class, 'hoja_ruta_id', 'id');
    }

    /**
     * The piezas that belong to the hoja.
     */
    public function piezas()
    {
        return $this->belongsToMany(\Pieza::class, \Subpieza::TABLE, 'hoja_ruta_id', 'pieza_id');
    }

}
