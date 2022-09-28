<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ServicioGrupoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_servicios_grupos';


    /**
     * Get the servicios for flashserviciosgrupo.
     */
    public function servicios()
    {
        return $this->hasMany(\Servicio::class, 'grupo_id', 'id');
    }


}
