<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class GrupoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'grupos';


    /**
     * The menus that belong to the grupo.
     */
    public function menus()
    {
        return $this->belongsToMany(\Menu::class, 'permisos', 'group_id', 'menu_id');
    }

    /**
     * The usuarios that belong to the grupo.
     */
    public function usuarios()
    {
        return $this->belongsToMany(\Usuario::class, 'usuarios_grupos', 'group_id', 'user_id');
    }

    /**
     * Get the permisos for grupo.
     */
    public function permisos()
    {
        return $this->hasMany(\Permiso::class, 'group_id', 'id');
    }

    /**
     * Get the usuariosGrupos for grupo.
     */
    public function usuarioGrupos()
    {
        return $this->hasMany(\UsuarioGrupo::class, 'group_id', 'id');
    }


}
