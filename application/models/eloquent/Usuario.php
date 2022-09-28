<?php

use App\Models\Base\UsuarioBase;

class Usuario extends UsuarioBase
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip_address', 'sucursal_id', 'username', 'password', 'salt', 'email', 'created_on', 'last_login', 'active', 'nombre', 'apellido', 'telefono', 'celular', 'foto', 'idioma'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $hidden = ['password', 'salt'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The relation to append to the model's array form.
     *
     * @var array
     */
    protected $nested = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to date format.
     *
     * @var array
     */
    protected $dates = ['created_on', 'last_login'];

}
