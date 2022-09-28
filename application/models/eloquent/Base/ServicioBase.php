<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ServicioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_servicios';


    /**
     * Get the servicioGrupo record associated with the flashservicio.
     */
    public function servicioGrupo()
    {
        return $this->belongsTo(\ServicioGrupo::class, 'grupo_id', 'id');
    }

    /**
     * The actualizacionPrecios that belong to the flashservicio.
     */
    public function actualizacionPrecios()
    {
        return $this->belongsToMany(\ActualizacionPrecio::class, 'flash_actualizacion_precios_servicios', 'servicio_id', 'actualizacion_id');
    }

    /**
     * Get the actualizacionPrecioServicios for flashservicio.
     */
    public function actualizacionPrecioServicios()
    {
        return $this->hasMany(\ActualizacionPrecioServicio::class, 'servicio_id', 'id');
    }

    /**
     * Get the clientePrecioEspecial for flashservicio.
     */
    public function clientePrecioEspecial()
    {
        return $this->hasMany(\ClientePrecioEspecial::class, 'servicio_id', 'id');
    }

    /**
     * Get the comprobanteServicios for flashservicio.
     */
    public function comprobanteServicios()
    {
        return $this->hasMany(\ComprobanteServicio::class, 'servicio_id', 'id');
    }


}
