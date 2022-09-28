<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ActualizacionPrecioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_actualizacion_precios';


    /**
     * Get the opcionesVariable record associated with the flashactualizacionprecio.
     */
    public function opcionVariable()
    {
        return $this->belongsTo(\OpcionVariable::class, 'tipo_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashactualizacionprecio.
     */
    public function usuarioCreacion()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_creacion_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashactualizacionprecio.
     */
    public function usuarioAprobacion()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_aprobacion_id', 'id');
    }

    /**
     * The clientePrecioEspecial that belong to the flashactualizacionprecio.
     */
    public function clientePreciosEspeciales()
    {
        return $this->belongsToMany(\ClientePrecioEspecial::class, 'flash_actualizacion_precios_especiales', 'actualizacion_id', 'precio_especial_id');
    }

    /**
     * The servicios that belong to the flashactualizacionprecio.
     */
    public function servicios()
    {
        return $this->belongsToMany(\Servicio::class, 'flash_actualizacion_precios_servicios', 'actualizacion_id', 'servicio_id');
    }

    /**
     * Get the actualizacionPreciosEspeciales for flashactualizacionprecio.
     */
    public function actualizacionPreciosEspeciales()
    {
        return $this->hasMany(\ActualizacionPrecioEspecial::class, 'actualizacion_id', 'id');
    }

    /**
     * Get the actualizacionPrecioServicios for flashactualizacionprecio.
     */
    public function actualizacionPreciosServicios()
    {
        return $this->hasMany(\ActualizacionPrecioServicio::class, 'actualizacion_id', 'id');
    }


}
