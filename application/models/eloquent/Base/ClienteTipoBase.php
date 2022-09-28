<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClienteTipoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes_tipos';


    /**
     * Get the clientes for flashclientestipo.
     */
    public function clientes()
    {
        return $this->hasMany(\Cliente::class, 'tipo_cliente_id', 'id');
    }


}
