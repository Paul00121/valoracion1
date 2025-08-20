<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clases';
    protected $primaryKey = 'idclase';

    protected $fillable = [
        'idcurso',
        'titulo',
        'tipo',
        'contenido',
        'duracion',
        'orden'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'idcurso');
    }
}