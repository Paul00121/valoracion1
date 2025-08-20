<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'idcompra';

    protected $fillable = [
        'idusuario',
        'idcurso',
        'monto_pagado',
        'descuento_aplicado',
        'comision_plataforma',
        'pago_profesor'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'idcurso');
    }
}