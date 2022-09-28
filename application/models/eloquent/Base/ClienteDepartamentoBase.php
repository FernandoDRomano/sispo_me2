<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClienteDepartamentoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes_departamentos';


    /**
     * Get the cliente record associated with the flashclientesdepartamento.
     */
    public function cliente()
    {
        return $this->belongsTo(\Cliente::class, 'cliente_id', 'id');
    }

    /**
     * Get the comprobantes for flashclientesdepartamento.
     */
    public function comprobantes()
    {
        return $this->hasMany(\Comprobante::class, 'departamento_id', 'id');
    }

    /**
     * Get the rendiciones for flashclientesdepartamento.
     */
    public function rendiciones()
    {
        return $this->hasMany(\Rendicion::class, 'departamento_id', 'id');
    }


}
