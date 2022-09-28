<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class OpcionBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'opciones';


    /**
     * Get the opcionesVariables for opcione.
     */
    public function opcionesVariables()
    {
        return $this->hasMany(\OpcionVariable::class, 'opcion_id', 'id');
    }


}
