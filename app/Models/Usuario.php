<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @mixin \Eloquent
 */

class Usuario extends Authenticatable
{
    use Notifiable;

    public $timestamps = true;

    protected $table = 'usuarios';
    protected $primaryKey = 'idu';

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'passwd',
        'idr',
        'idp',
        'verificado'
    ];

    protected $hidden = [
        'passwd',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idr');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idp');
    }

    public function cursosComoProfesor()
    {
        return $this->hasMany(Curso::class, 'id_profesor');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'idusuario');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'idusuario');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'idusuario');
    }

    public function sesionesActivas()
    {
        return $this->hasMany(SesionesActivas::class, 'idu');
    }
    public function getAuthPassword()
    {
        return $this->passwd;
    }
}