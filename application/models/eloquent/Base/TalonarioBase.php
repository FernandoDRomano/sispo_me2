<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class TalonarioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_talonarios';


    /**
     * Get the talonarioResponsable record associated with the flashpiezastalonario.
     */
    public function talonarioResponsable()
    {
        return $this->belongsTo(\TalonarioResponsable::class, 'responsable_id', 'id');
    }

    /**
     * Get the comprobantes for flashpiezastalonario.
     */
    public function comprobantes()
    {
        return $this->hasMany(\Comprobante::class, 'talonario_id', 'id');
    }

    /**
     * Get the comprobanteGenerados for flashpiezastalonario.
     */
    public function comprobanteGenerados()
    {
        return $this->hasMany(\ComprobanteGenerado::class, 'talonario_id', 'id');
    }


}
