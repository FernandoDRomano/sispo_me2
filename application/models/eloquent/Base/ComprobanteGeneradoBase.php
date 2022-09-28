<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ComprobanteGeneradoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_comprobantes_ingresos_generados';


    /**
     * Get the talonario record associated with the flashcomprobantesingresosgenerado.
     */
    public function talonario()
    {
        return $this->belongsTo(\Talonario::class, 'talonario_id', 'id');
    }


}
