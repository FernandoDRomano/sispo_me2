<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class SubpiezaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_subpiezas';


    /**
     * Get the hojasRutum record associated with the flashsubpieza.
     */
    public function hojas()
    {
        return $this->belongsTo(\Hoja::class, 'hoja_ruta_id', 'id');
    }

    /**
     * Get the pieza record associated with the flashsubpieza.
     */
    public function pieza()
    {
        return $this->belongsTo(\Pieza::class, 'pieza_id', 'id');
    }

    /**
     * The rendiciones that belong to the flashsubpieza.
     */
    public function rendiciones()
    {
        return $this->belongsToMany(\Rendicion::class, 'flash_rendiciones_piezas', 'subpieza_id', 'rendicion_id');
    }

    /**
     * Get the rendicionPiezas for flashsubpieza.
     */
    public function rendicionPiezas()
    {
        return $this->hasMany(\RendicionPieza::class, 'subpieza_id', 'id');
    }


}
