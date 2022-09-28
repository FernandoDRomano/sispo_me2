<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class DistribuidorBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'flash_distribuidores';


    /**
     * Get the hojas for flashdistribuidore.
     */
    public function hojas()
    {
        return $this->hasMany(\Hoja::class, 'distribuidor_id', 'id');
    }


}
