<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class OpcionVariableBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'opciones_variables';


    /**
     * Get the opcione record associated with the opcionesvariable.
     */
    public function opcione()
    {
        return $this->belongsTo(\Opcion::class, 'opcion_id', 'id');
    }

    /**
     * Get the actualizacionPrecios for opcionesvariable.
     */
    public function actualizacionPrecios()
    {
        return $this->hasMany(\ActualizacionPrecio::class, 'tipo_id', 'id');
    }

    /**
     * Get the piezas for opcionesvariable.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'verifico_id', 'id');
    }


}
