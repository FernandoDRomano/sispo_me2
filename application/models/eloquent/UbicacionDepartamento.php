<?php

use App\Models\Base\UbicacionDepartamentoBase;

class UbicacionDepartamento extends UbicacionDepartamentoBase
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provincia_id', 'nombre'];

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
