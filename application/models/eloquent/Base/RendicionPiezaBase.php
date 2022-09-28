<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class RendicionPiezaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_rendiciones_piezas';


    /**
     * Get the rendicione record associated with the flashrendicionespieza.
     */
    public function rendicion()
    {
        return $this->belongsTo(\Rendicion::class, 'rendicion_id', 'id');
    }

    /**
     * Get the subpieza record associated with the flashrendicionespieza.
     */
    public function subpieza()
    {
        return $this->belongsTo(\Subpieza::class, 'subpieza_id', 'id');
    }


}
