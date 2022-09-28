<?php

use App\Models\Base\PiezaEstadoBase;

class PiezaEstado extends PiezaEstadoBase
{
    const ESTADOS_INICIALES = 1;
    const ESTADOS_RENDICIONES = 2;
    const ESTADOS_ORGANIZATIVOS = 3;
    const ESTADOS_DESPACHO = 4;
    const ESTADOS_TRACKEO = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

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
    public $timestamps = false;

    /**
     * The attributes that should be casted to date format.
     *
     * @var array
     */
    protected $dates = [];

}
