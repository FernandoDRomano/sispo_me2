<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class NovedadBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_novedades';


    /**
     * Get the piezaEstadoVariable record associated with the flashpiezasnovedade.
     */
    public function estadoActual()
    {
        return $this->belongsTo(\PiezaEstadoVariable::class, 'estado_actual_id', 'id');
    }

    /**
     * Get the piezaEstadoVariable record associated with the flashpiezasnovedade.
     */
    public function estadoNuevo()
    {
        return $this->belongsTo(\PiezaEstadoVariable::class, 'estado_nuevo_id', 'id');
    }

    /**
     * Get the subpieza record associated with the flashpiezasnovedade.
     */
    public function pieza()
    {
        return $this->belongsTo(\Pieza::class, 'pieza_id', 'id');
    }

    /**
     * Get the usuario record associated with the novedad.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_id', 'id');
    }

}
