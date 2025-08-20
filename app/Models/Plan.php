<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';
    protected $primaryKey = 'idp';

    protected $fillable = [
        'nombre',
        'sesiones_permitidas',
        'descripcion',
        'precio',
        'descuento_cursos'
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'idp');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class, 'idplan');
    }
}