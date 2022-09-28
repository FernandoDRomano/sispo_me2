<?php

use App\Models\Base\TalonarioResponsableBase;

class TalonarioResponsable extends TalonarioResponsableBase
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sucursal_id', 'apellido', 'nombre'];

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
    protected $dates = [];

    public function getApellidoNombreAttribute()
    {
        return $this->nombre . " " . $this->apellido;
    }

}
