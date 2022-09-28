<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ActualizacionPrecioEspecialBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_actualizacion_precios_especiales';


    /**
     * Get the actualizacionPrecio record associated with the flashactualizacionpreciosespeciale.
     */
    public function actualizacionPrecio()
    {
        return $this->belongsTo(\ActualizacionPrecio::class, 'actualizacion_id', 'id');
    }

    /**
     * Get the clientePreciosEspeciale record associated with the flashactualizacionpreciosespeciale.
     */
    public function clientePrecioEspecial()
    {
        return $this->belongsTo(\ClientePrecioEspecial::class, 'precio_especial_id', 'id');
    }


}
