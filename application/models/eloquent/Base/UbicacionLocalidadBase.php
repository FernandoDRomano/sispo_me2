<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class UbicacionLocalidadBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'ubicacion_localidades';


    /**
     * Get the ubicacionDepartamento record associated with the ubicacionlocalidade.
     */
    public function ubicacionDepartamento()
    {
        return $this->belongsTo(\UbicacionDepartamento::class, 'departamento_id', 'id');
    }


}
