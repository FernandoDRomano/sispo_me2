<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class DespachoPiezaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_piezas_despacho_piezas';


    /**
     * Get the despacho record associated with the flashpiezasdespachopieza.
     */
    public function despacho()
    {
        return $this->belongsTo(\Despacho::class, 'despacho_id', 'id');
    }

    /**
     * Get the pieza record associated with the flashpiezasdespachopieza.
     */
    public function pieza()
    {
        return $this->belongsTo(\Pieza::class, 'pieza_id', 'id');
    }


}
