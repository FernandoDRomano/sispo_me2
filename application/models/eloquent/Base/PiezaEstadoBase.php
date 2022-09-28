<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class PiezaEstadoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_estados';


    /**
     * Get the piezaEstadoVariables for flashpiezasestado.
     */
    public function piezaEstado()
    {
        return $this->hasMany(\PiezaEstadoVariable::class, 'pieza_estado_id', 'id');
    }


}
