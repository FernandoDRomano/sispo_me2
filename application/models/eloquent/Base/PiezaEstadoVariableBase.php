<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class PiezaEstadoVariableBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_estados_variables';


    /**
     * Get the piezasEstado record associated with the flashpiezasestadosvariable.
     */
    public function estado()
    {
        return $this->belongsTo(\PiezaEstado::class, 'pieza_estado_id', 'id');
    }

    /**
     * Get the piezas for flashpiezasestadosvariable.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'estado_id', 'id');
    }

    /**
     * Get the piezasNovedades for flashpiezasestadosvariable.
     */
    public function novedadEstadoActual()
    {
        return $this->hasMany(\Novedad::class, 'estado_actual_id', 'id');
    }

    /**
     * Get the piezasNovedades for flashpiezasestadosvariable.
     */
    public function novedadEstadoNuevo()
    {
        return $this->hasMany(\Novedad::class, 'estado_nuevo_id', 'id');
    }

    /**
     * Get the subpiezas for flashpiezasestadosvariable.
     */
    public function subpiezas()
    {
        return $this->hasMany(\Subpieza::class, 'estado_id', 'id');
    }


}
