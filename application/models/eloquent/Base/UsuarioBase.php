<?php namespace App\Models\Base;

use App\Models\Classes\Model;

abstract class UsuarioBase extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE;
    const TABLE = 'users';


    /**
     * Get the sucursale record associated with the usuario.
     */
    public function sucursal()
    {
        return $this->belongsTo(\Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * The grupos that belong to the usuario.
     */
    public function grupos()
    {
        return $this->belongsToMany(\Grupo::class, 'usuarios_grupos', 'user_id', 'group_id');
    }

    /**
     * Get the auditoria for usuario.
     */
    public function auditoria()
    {
        return $this->hasMany(\Auditoria::class, 'user_id', 'id');
    }

    /**
     * Get the actualizacionPrecios for usuario.
     */
    public function actualizacionPreciosCreacion()
    {
        return $this->hasMany(\ActualizacionPrecio::class, 'usuario_creacion_id', 'id');
    }

    /**
     * Get the actualizacionPrecios for usuario.
     */
    public function actualizacionPreciosAprobacion()
    {
        return $this->hasMany(\ActualizacionPrecio::class, 'usuario_aprobacion_id', 'id');
    }

    /**
     * Get the piezas for usuario.
     */
    public function piezas()
    {
        return $this->hasMany(\Pieza::class, 'usuario_id', 'id');
    }

    /**
     * Get the despachos for usuario.
     */
    public function despachosOrigen()
    {
        return $this->hasMany(\Despacho::class, 'usuario_origen_id', 'id');
    }

    /**
     * Get the despachos for usuario.
     */
    public function despachosDestino()
    {
        return $this->hasMany(\Despacho::class, 'usuario_destino_id', 'id');
    }

    /**
     * Get the rendiciones for usuario.
     */
    public function rendiciones()
    {
        return $this->hasMany(\Rendicion::class, 'usuario_id', 'id');
    }

    /**
     * Get the loginAttempts for usuario.
     */
    public function loginAttempts()
    {
        return $this->hasMany(\LoginAttempt::class, 'user_id', 'id');
    }

    /**
     * Get the usuariosGrupos for usuario.
     */
    public function usuarioGrupos()
    {
        return $this->hasMany(\UsuarioGrupo::class, 'user_id', 'id');
    }


}
