<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Categoria::all();
    }

    public function store(Request $request)
    {
        return Categoria::create($request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]));
    }

    public function show(Categoria $categoria)
    {
        return $categoria->load('cursos');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($request->validate([
            'nombre' => 'sometimes|string|max:100',
            'descripcion' => 'nullable|string'
        ]));
        return $categoria;
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->noContent();
    }
}
