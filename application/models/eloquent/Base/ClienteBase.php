<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClienteBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes';


    /**
     * Get the clienteEstado record associated with the flashcliente.
     */
    public function clienteEstado()
    {
        return $this->belongsTo(\ClienteEstado::class, 'cliente_estado_id', 'id');
    }

    /**
     * Get the clienteTipo record associated with the flashcliente.
     */
    public function clienteTipo()
    {
        return $this->belongsTo(\ClienteTipo::class, 'tipo_cliente_id', 'id');
    }

    /**
     * Get the clienteContactos for flashcliente.
     */
    public function clienteContactos()
    {
        return $this->hasMany(\ClienteContacto::class, 'cliente_id', 'id');
    }

    /**
     * Get the clienteDepartamentos for flashcliente.
     */
    public function clienteDepartamentos()
    {
        return $this->hasMany(\ClienteDepartamento::class, 'cliente_id', 'id');
    }

    /**
     * Get the clientePrecioEspecial for flashcliente.
     */
    public function clientePreciosEspeciales()
    {
        return $this->hasMany(\ClientePrecioEspecial::class, 'cliente_id', 'id');
    }

    /**
     * Get the clienteResponsables for flashcliente.
     */
    public function clienteResponsables()
    {
        return $this->hasMany(\ClienteResponsable::class, 'cliente_id', 'id');
    }

    /**
     * Get the comprobantes for flashcliente.
     */
    public function comprobantes()
    {
        return $this->hasMany(\Comprobante::class, 'cliente_id', 'id');
    }

    /**
     * Get the rendiciones for flashcliente.
     */
    public function rendiciones()
    {
        return $this->hasMany(\Rendicion::class, 'clientes_id', 'id');
    }


}
