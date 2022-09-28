<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class UbicacionDepartamentoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'ubicacion_departamentos';


    /**
     * Get the ubicacionProvincium record associated with the ubicaciondepartamento.
     */
    public function ubicacionProvincia()
    {
        return $this->belongsTo(\UbicacionProvincia::class, 'provincia_id', 'id');
    }

    /**
     * Get the ubicacionLocalidades for ubicaciondepartamento.
     */
    public function ubicacionLocalidades()
    {
        return $this->hasMany(\UbicacionLocalidad::class, 'departamento_id', 'id');
    }


}
