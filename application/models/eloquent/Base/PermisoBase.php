<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class PermisoBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'permisos';


    /**
     * Get the menu record associated with the permiso.
     */
    public function menu()
    {
        return $this->belongsTo(\Menu::class, 'menu_id', 'id');
    }

    /**
     * Get the grupo record associated with the permiso.
     */
    public function grupo()
    {
        return $this->belongsTo(\Grupo::class, 'group_id', 'id');
    }


}
