<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Resena::with(['usuario', 'curso'])->get();
    }

    public function store(Request $request)
    {
        return Resena::create($request->validate([
            'idusuario' => 'required|exists:usuarios,idu',
            'idcurso' => 'required|exists:cursos,idcurso',
            'puntuacion' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string'
        ]));
    }

    public function show(Resena $resena)
    {
        return $resena->load(['usuario', 'curso']);
    }

    public function destroy(Resena $resena)
    {
        $resena->delete();
        return response()->noContent();
    }
}
