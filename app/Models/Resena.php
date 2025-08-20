<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $table = 'reseñas';
    protected $primaryKey = 'idreseña';

    protected $fillable = [
        'idusuario',
        'idcurso',
        'puntuacion',
        'comentario'
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