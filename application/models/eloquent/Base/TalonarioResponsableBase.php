<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class TalonarioResponsableBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_talonarios_responsables';


    /**
     * Get the sucursale record associated with the flashpiezastalonariosresponsable.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the talonarios for flashpiezastalonariosresponsable.
     */
    public function talonarios()
    {
        return $this->hasMany(\Talonario::class, 'responsable_id', 'id');
    }


}
