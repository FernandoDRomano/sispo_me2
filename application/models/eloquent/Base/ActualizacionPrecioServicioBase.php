<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ActualizacionPrecioServicioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_actualizacion_precios_servicios';


    /**
     * Get the actualizacionPrecio record associated with the flashactualizacionpreciosservicio.
     */
    public function actualizacionPrecio()
    {
        return $this->belongsTo(\ActualizacionPrecio::class, 'actualizacion_id', 'id');
    }

    /**
     * Get the servicio record associated with the flashactualizacionpreciosservicio.
     */
    public function servicio()
    {
        return $this->belongsTo(\Servicio::class, 'servicio_id', 'id');
    }


}
