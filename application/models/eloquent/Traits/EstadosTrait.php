<?php namespace App\Models\Traits;

trait EstadosTrait
{
    public function getEstadoNombreAttribute()
    {
        return self::estadoNombre($this->estado);
    }

    public static function estadoNombre($estado)
    {
        return self::$estadoNombre[$estado];
    }

}