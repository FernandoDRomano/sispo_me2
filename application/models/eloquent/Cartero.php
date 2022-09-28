<?php

use App\Models\Base\CarteroBase;

class Cartero extends CarteroBase
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sucursal_id', 'apellido_nombre', 'domicilio', 'localidad', 'codigo_postal', 'telefono', 'activo', 'fecha_alta', 'fecha_baja'];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'create';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'update';

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
    protected $hidden = [];

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
    public $timestamps = true;

    /**
     * The attributes that should be casted to date format.
     *
     * @var array
     */
    public $dates = ['fecha_alta', 'fecha_baja'];

}
