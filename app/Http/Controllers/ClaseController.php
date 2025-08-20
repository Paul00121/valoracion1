<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Clase::with('curso')->get();
    }

    public function store(Request $request)
    {
        return Clase::create($request->validate([
            'idcurso' => 'required|exists:cursos,idcurso',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:video,audio,texto',
            'orden' => 'required|integer'
        ]));
    }

    public function show(Clase $clase)
    {
        return $clase->load('curso');
    }

    public function update(Request $request, Clase $clase)
    {
        $clase->update($request->validate([
            'titulo' => 'sometimes|string|max:255',
            'tipo' => 'sometimes|in:video,audio,texto',
            'orden' => 'sometimes|integer'
        ]));
        return $clase;
    }

    public function destroy(Clase $clase)
    {
        $clase->delete();
        return response()->noContent();
    }
}
