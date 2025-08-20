<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionPaypal extends Model
{
    use HasFactory;

    protected $table = 'transacciones_paypal';
    protected $primaryKey = 'idtransaccion';

    protected $fillable = [
        'idusuario',
        'tipo',
        'idsuscripcion',
        'idcompra',
        'id_paypal',
        'estado',
        'monto',
        'datos_completos'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario');
    }

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'idsuscripcion');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idcompra');
    }
}