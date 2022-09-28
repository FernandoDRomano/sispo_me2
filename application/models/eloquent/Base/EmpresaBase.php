<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class EmpresaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_empresas';


    /**
     * Get the comprobantes for flashempresa.
     */
    public function comprobantes()
    {
        return $this->hasMany(\Comprobante::class, 'empresa_id', 'id');
    }


}
