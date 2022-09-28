<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class PiezaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas';


    /**
     * Get the comprobante record associated with the flashpieza.
     */
    public function comprobante()
    {
        return $this->comprobanteServicio->comprobante();
    }

    /**
     * Get the piezaEstadoVariable record associated with the flashpieza.
     */
    public function estado()
    {
        return $this->belongsTo(\PiezaEstadoVariable::class, 'estado_id', 'id');
    }

    /**
     * Get the piezasTipo record associated with the flashpieza.
     */
    public function tipo()
    {
        return $this->belongsTo(\PiezaTipo::class, 'tipo_id', 'id');
    }

    /**
     * Get the comprobanteServicio record associated with the flashpieza.
     */
    public function comprobanteServicio()
    {
        return $this->belongsTo(\ComprobanteServicio::class, 'servicio_id', 'id');
    }

    /**
     * Get the sucursale record associated with the flashpieza.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the opcionesVariable record associated with the flashpieza.
     */
    public function opcionVariable()
    {
        return $this->belongsTo(\OpcionVariable::class, 'verifico_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashpieza.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_id', 'id');
    }

    /**
     * The despachos that belong to the flashpieza.
     */
    public function despachos()
    {
        return $this->belongsToMany(\Despacho::class, 'flash_piezas_despacho_piezas', 'pieza_id', 'despacho_id');
    }

    /**
     * Get the despachoPiezas for flashpieza.
     */
    public function despachoPiezas()
    {
        return $this->hasMany(\DespachoPieza::class, 'pieza_id', 'id');
    }

    /**
     * Get the subpiezas for flashpieza.
     */
    public function subpiezas()
    {
        return $this->hasMany(\Subpieza::class, 'pieza_id', 'id');
    }

    /**
     * Get the piezasNovedades for flashpieza.
     */
    public function novedades()
    {
        return $this->hasMany(\Novedad::class, 'pieza_id', 'id');
    }


}
