<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recuperacion extends Model
{
    use HasFactory;

    protected $table = 'recuperacion';

    protected $fillable = [
        'correo',
        'codigo',
        'token',
        'expiracion',
        'usado'
    ];

    protected $dates = [
        'expiracion'
    ];
}