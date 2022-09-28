<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClienteEstadoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes_estados';


    /**
     * Get the clientes for flashclientesestado.
     */
    public function clientes()
    {
        return $this->hasMany(\Cliente::class, 'cliente_estado_id', 'id');
    }


}
