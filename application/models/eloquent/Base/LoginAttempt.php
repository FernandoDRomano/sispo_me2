<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class LoginAttemptBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'login_attempts';


    /**
     * Get the user record associated with the loginattempt.
     */
    public function usuario()
    {
        return $this->belongsTo(\Usuario::class, 'user_id', 'id');
    }


}
