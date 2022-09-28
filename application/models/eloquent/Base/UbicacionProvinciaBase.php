<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class UbicacionProvinciaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'ubicacion_provincias';


    /**
     * Get the ubicacionDepartamentos for ubicacionprovincia.
     */
    public function ubicacionDepartamentos()
    {
        return $this->hasMany(\UbicacionDepartamento::class, 'provincia_id', 'id');
    }


}
