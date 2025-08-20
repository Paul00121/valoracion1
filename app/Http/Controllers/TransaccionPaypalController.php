<?php

namespace App\Http\Controllers;

use App\Models\TransaccionPaypal;
use Illuminate\Http\Request;

class TransaccionPaypalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TransaccionPaypal::with(['usuario', 'suscripcion', 'compra'])->get();
    }

    public function store(Request $request)
    {
        return TransaccionPaypal::create($request->validate([
            'idusuario' => 'required|exists:usuarios,idu',
            'id_paypal' => 'required|string',
            'monto' => 'required|numeric',
            'tipo' => 'required|in:suscripcion,compra_curso'
        ]));
    }

    public function show(TransaccionPaypal $transaccionPaypal)
    {
        return $transaccionPaypal->load(['usuario', 'suscripcion', 'compra']);
    }
}
