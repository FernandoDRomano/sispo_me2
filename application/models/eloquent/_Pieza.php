<?php

use App\Models\Base\PiezaBase;
use App\Models\Traits\EstadosTrait;

class Pieza extends PiezaBase
{
    use EstadosTrait;

    const ESTADO_EN_GESTION = 1;
    const ESTADO_EN_DISTRIBUCION = 2;
    const ESTADO_EN_TRNSITO = 15;
    const ESTADO_RECIBIDA = 3;
    const ESTADO_NO_EXISTE_NUMERO = 4;
    const ESTADO_NO_EXISTE_DIRECCION = 5;
    const ESTADO_DESTINO_DESCONOCIDO = 6;
    const ESTADO_DOMICILIO_ABANDONADO = 7;
    const ESTADO_DOMICILIO_INSUFICIENTE = 8;
    const ESTADO_SE_MUDO = 9;
    const ESTADO_FALLECIO = 10;
    const ESTADO_SE_NIEGA_A_RECIBIR = 11;
    const ESTADO_OTRO = 12;
    const ESTADO_ENTREGADA = 13;
    const ESTADO_NO_RESPONDE = 14;
    const ESTADO_PERDIDA = 16;
    const ESTADO_PENDIENTE_DE_RECEPCION = 62;
    
    const TIPO_SIMPLE = 1;
    const TIPO_NORMAL = 2;

    private static $estadoNombre = [
        self::ESTADO_EN_GESTION => "En Gestión",
        self::ESTADO_EN_DISTRIBUCION => "En Distribución",
        self::ESTADO_EN_TRNSITO => "En Tránsito",
        self::ESTADO_RECIBIDA => "Recibida",
        self::ESTADO_NO_EXISTE_NUMERO => "No Existe el número",
        self::ESTADO_NO_EXISTE_DIRECCION => "No existe la dirección",
        self::ESTADO_DESTINO_DESCONOCIDO => "Destino Desconocido",
        self::ESTADO_DOMICILIO_ABANDONADO => "Domicilio Abandonado",
        self::ESTADO_DOMICILIO_INSUFICIENTE => "Domicilio Insuficiente",
        self::ESTADO_SE_MUDO => "Se mudó",
        self::ESTADO_FALLECIO => "Falleció",
        self::ESTADO_SE_NIEGA_A_RECIBIR => "Se niega a recibir",
        self::ESTADO_OTRO => "Otro",
        self::ESTADO_ENTREGADA => "Entregada",
        self::ESTADO_NO_RESPONDE => "No Responde",
        self::ESTADO_PENDIENTE_DE_RECEPCION => "Pendiente de Recepción",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario_id',
        'servicio_id',
        'tipo_id',
        'sucursal_id',
        'estado_id',
        'comprobante_ingreso_id',
        'cantidad',
        'barcode',
        'barcode_externo',
        'destinatario',
        'domicilio',
        'codigo_postal',
        'localidad',
        'vista',
        'recibio',
        'documento',
        'vinculo',
        'datos_varios',
        'datos_varios_1',
        'datos_varios_2'
    ];

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


    public function getDescripcionAttribute()
    {
        return $this->tipo_id == self::TIPO_SIMPLE ? $this->datos_varios : "{$this->destinatario} - {$this->domicilio} - {$this->codigo_postal} - {$this->localidad}";
    }

}
