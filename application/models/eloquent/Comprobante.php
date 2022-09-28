<?php

use App\Models\Base\ComprobanteBase;

class Comprobante extends ComprobanteBase
{

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
    protected $fillable = ['talonario_id', 'empresa_id', 'sucursal_id', 'cliente_id', 'departamento_id', 'numero', 'fecha_pedido', 'cantidad', 'importe'];

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
    public $dates = ['fecha_pedido'];
    public function getDisponibleAttribute()
    {
        $usadas = 0;
        foreach ($this->comprobanteServicios as $comprobanteServicio)
        {
            $usadas = $usadas + $comprobanteServicio->cantidad - $comprobanteServicio->disponible;
        }
        return $this->cantidad - $usadas;
    }
}

