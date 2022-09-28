<?php

use App\Models\Base\HojaBase;
use App\Models\Traits\EstadosTrait;
use App\Models\Traits\BarcodeTrait;

class Hoja extends HojaBase
{
    use EstadosTrait;
    use BarcodeTrait;

    const ESTADO_INICIADA  = 1;
    const ESTADO_CERRADA   = 2;
    const ESTADO_BAJA = 3;
    const ESTADO_ARCHIVADA = 4;
    const ESTADO_CANCELADA = 5;

    private static $estadoNombre = [
        self::ESTADO_INICIADA  => "Iniciada",
        self::ESTADO_CERRADA   => "Cerrada",
        self::ESTADO_BAJA => "De Baja",
        self::ESTADO_ARCHIVADA => "Archivada",
        self::ESTADO_CANCELADA => "Cancelada",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sucursal_id', 'cartero_id', 'zona_id', 'distribuidor_id', 'transporte_id', 'estado', 'observaciones', 'fecha_entrega', 'fecha_baja'];

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
    public $dates = ['fecha_entrega', 'fecha_baja'];

}
