<?php

use App\Models\Base\ClienteBase;
use App\Models\Traits\EstadosTrait;

class Cliente extends ClienteBase
{
    use EstadosTrait;

    const ESTADO_ACTIVO = 1;
    const ESTADO_INACTIVO = 2;
    const ESTADO_SUSPENDIDO = 3;

    private static $estadoNombre = [
        self::ESTADO_ACTIVO => "Activo",
        self::ESTADO_INACTIVO => "Inactivo",
        self::ESTADO_SUSPENDIDO => "Suspendido",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_estado_id', 'tipo_cliente_id', 'nombre', 'nombre_fantasia', 'iva', 'cuit', 'domicilio', 'localidad', 'provincia', 'codigo_postal', 'telefonos', 'fecha_ingreso', 'observaciones', 'ejecutivo_comercial'];

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
    public $dates = ['fecha_ingreso'];

}
