<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificacionEmail extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'verificacion_email';

    protected $fillable = [
        'usuario_id',
        'token',
        'expiracion',
        'usado'
    ];

    protected $dates = [
        'expiracion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'idu');
    }
}