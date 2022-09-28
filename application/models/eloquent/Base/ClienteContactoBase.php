<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class ClienteContactoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_clientes_contactos';


    /**
     * Get the cliente record associated with the flashclientescontacto.
     */
    public function cliente()
    {
        return $this->belongsTo(\Cliente::class, 'cliente_id', 'id');
    }


}
