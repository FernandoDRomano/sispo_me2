<?php

use App\Models\Base\DespachoBase;
use App\Models\Traits\EstadosTrait;
use App\Models\Traits\BarcodeTrait;

class Despacho extends DespachoBase
{
    use EstadosTrait;
    use BarcodeTrait;

    const ESTADO_INICIADO = 1;
    const ESTADO_ENVIADO = 2;
    const ESTADO_RECIBIDO = 3;
    const ESTADO_VERIFICADO = 4;
    const ESTADO_ARCHIVADO_ORIGEN = 5;
    const ESTADO_ARCHIVADO_DESTINO = 7;
    const ESTADO_CANCELADO = 6;

    private static $estadoNombre = [
        self::ESTADO_INICIADO => "Inicial",
        self::ESTADO_ENVIADO => "Enviado",
        self::ESTADO_RECIBIDO => "Recibido",
        self::ESTADO_VERIFICADO => "Verificado",
        self::ESTADO_ARCHIVADO_ORIGEN => "Archivado en Origen",
        self::ESTADO_ARCHIVADO_DESTINO => "Archivado en Destino",
        self::ESTADO_CANCELADO => "Cancelado",
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['origen_id', 'destino_id', 'usuario_origen_id', 'usuario_destino_id', 'transporte_id', 'fecha_envio', 'fecha_recepcion', 'estado'];

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
    public $dates = ['fecha_envio', 'fecha_recepcion'];

    public function getVerificadasAttribute($despacho_id)
    {
        return $this->piezas()->where('estado_id', Pieza::ESTADO_EN_GESTION)
                              ->orWhere('estado_id', Pieza::ESTADO_PERDIDA)
                                ->get()->count();
    }
}
