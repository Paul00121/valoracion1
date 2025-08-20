<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionesActivas extends Model
{
    use HasFactory;

    protected $table = 'sesiones_activas';
    protected $primaryKey = 'ids';

    protected $fillable = [
        'idu',
        'session_id',
        'ip_address',
        'user_agent'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idu');
    }
}