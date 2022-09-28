<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class RendicioneBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_rendiciones';


    /**
     * Get the clienteDepartamento record associated with the flashrendicione.
     */
    public function clienteDepartamento()
    {
        return $this->belongsTo(\ClienteDepartamento::class, 'departamento_id', 'id');
    }

    /**
     * Get the cliente record associated with the flashrendicione.
     */
    public function cliente()
    {
        return $this->belongsTo(\Cliente::class, 'clientes_id', 'id');
    }

    /**
     * Get the usuario record associated with the flashrendicione.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'usuario_id', 'id');
    }

    /**
     * The subpiezas that belong to the flashrendicione.
     */
    public function subpiezas()
    {
        return $this->belongsToMany(\Subpieza::class, 'flash_rendiciones_piezas', 'rendicion_id', 'subpieza_id');
    }

    /**
     * Get the rendicionPiezas for flashrendicione.
     */
    public function rendicionPiezas()
    {
        return $this->hasMany(\RendicionPieza::class, 'rendicion_id', 'id');
    }


}
