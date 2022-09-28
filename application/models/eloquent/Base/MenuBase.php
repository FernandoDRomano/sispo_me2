<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class MenuBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'menus';


    /**
     * The grupos that belong to the menu.
     */
    public function grupos()
    {
        return $this->belongsToMany(\Grupo::class, 'permisos', 'menu_id', 'group_id');
    }

    /**
     * Get the permisos for menu.
     */
    public function permisos()
    {
        return $this->hasMany(\Permiso::class, 'menu_id', 'id');
    }


}
