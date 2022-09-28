<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ComprobanteServicioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_comprobantes_ingresos_servicios';


    /**
     * Get the comprobante record associated with the flashcomprobantesingresosservicio.
     */
    public function comprobante()
    {
        return $this->belongsTo(\Comprobante::class, 'comprobante_ingreso_id', 'id');
    }

    /**
     * Get the servicio record associated with the flashcomprobantesingresosservicio.
     */
    public function servicio()
    {
        return $this->belongsTo(\Servicio::class, 'servicio_id', 'id');
    }

    /**
     * Get the piezas for flashcomprobantesingresosservicio.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'servicio_id', 'id');
    }


}
