<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class UsuarioGrupoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'users_groups';


    /**
     * Get the usuario record associated with the usuariosgroup.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'user_id', 'id');
    }

    /**
     * Get the grupo record associated with the usuariosgroup.
     */
    public function grupo()
    {
        return $this->belongsTo(\Grupo::class, 'group_id', 'id');
    }


}
