<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class PiezaTipoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_tipos';


    /**
     * Get the piezas for flashpiezastipo.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'tipo_id', 'id');
    }


}
