<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';
    protected $primaryKey = 'idsuscripcion';

    protected $fillable = [
        'idusuario',
        'idplan',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idplan', 'idp');
    }
}