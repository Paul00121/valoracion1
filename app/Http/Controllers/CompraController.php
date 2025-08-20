<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Compra::with(['usuario', 'curso'])->get();
    }

    public function store(Request $request)
    {
        return Compra::create($request->validate([
            'idusuario' => 'required|exists:usuarios,idu',
            'idcurso' => 'required|exists:cursos,idcurso',
            'monto_pagado' => 'required|numeric',
            'descuento_aplicado' => 'required|numeric'
        ]));
    }

    public function show(Compra $compra)
    {
        return $compra->load(['usuario', 'curso']);
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return response()->noContent();
    }
}
