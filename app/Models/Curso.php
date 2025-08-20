<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';
    protected $primaryKey = 'idcurso';

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'id_profesor',
        'id_categoria',
        'puntuacion',
        'es_gratis'
    ];

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'id_profesor', 'idu');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'idcurso');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'idcurso');
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class, 'idcurso');
    }
}