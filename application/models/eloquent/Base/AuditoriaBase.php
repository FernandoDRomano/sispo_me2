<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class AuditoriaBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'auditoria';


    /**
     * Get the usuario record associated with the auditorium.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'user_id', 'id');
    }


}
