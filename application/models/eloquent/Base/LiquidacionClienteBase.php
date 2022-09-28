<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class LiquidacionClienteBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_liquidaciones_clientes';


    /**
     * Get the clienteDepartamento record associated with the flashcomprobantesingreso.
     */
    public function clienteDepartamento()
    {
        return $this->belongsTo(\ClienteDepartamento::class, 'departamento_id', 'id');
    }

    /**
     * Get the cliente record associated with the flashcomprobantesingreso.
     */
    public function cliente()
    {
        return $this->belongsTo(\Cliente::class, 'cliente_id', 'id');
    }

    /**
     * Get the flashEmpresa record associated with the flashcomprobantesingreso.
     */
    public function empresa()
    {
        return $this->belongsTo(\Empresa::class, 'empresa_id', 'id');
    }

    /**
     * Get the talonario record associated with the flashcomprobantesingreso.
     */
//    public function talonario()
//    {
//        return $this->belongsTo(\Talonario::class, 'talonario_id', 'id');
//    }
//
//    /**
//     * Get the sucursale record associated with the flashcomprobantesingreso.
//     */
//    public function sucursal()
//    {
//        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
//    }
//
//    /**
//     * Get the comprobanteServicios for flashcomprobantesingreso.
//     */
//    public function comprobanteServicios()
//    {
//        return $this->hasMany(\ComprobanteServicio::class, 'comprobante_ingreso_id', 'id');
//    }
//
//    /**
//     * Get the piezas for flashcomprobantesingreso.
//     */
//    public function piezas()
//    {
//        return $this->hasMany(\Pieza::class, 'comprobante_id', 'id');
//    }


}
