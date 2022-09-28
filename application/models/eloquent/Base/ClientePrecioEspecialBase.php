<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClientePrecioEspecialBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes_precios_especiales';


    /**
     * Get the cliente record associated with the flashclientespreciosespeciale.
     */
    public function cliente()
    {
        return $this->belongsTo(\Cliente::class, 'cliente_id', 'id');
    }

    /**
     * Get the servicio record associated with the flashclientespreciosespeciale.
     */
    public function servicio()
    {
        return $this->belongsTo(\Servicio::class, 'servicio_id', 'id');
    }

    /**
     * The actualizacionPrecios that belong to the flashclientespreciosespeciale.
     */
    public function actualizacionPrecios()
    {
        return $this->belongsToMany(\ActualizacionPrecio::class, 'flash_actualizacion_precios_especiales', 'precio_especial_id', 'actualizacion_id');
    }

    /**
     * Get the actualizacionPreciosEspeciales for flashclientespreciosespeciale.
     */
    public function actualizacionPreciosEspeciales()
    {
        return $this->hasMany(\ActualizacionPrecioEspecial::class, 'precio_especial_id', 'id');
    }


}
