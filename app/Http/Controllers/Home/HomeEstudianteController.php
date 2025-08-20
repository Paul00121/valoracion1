<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeEstudianteController extends Controller
{
    public function index()
    {
        // Obtener los cursos comprados por el estudiante
        $cursos = Auth::user()->compras()->with('curso.profesor')->get()->pluck('curso');

        return view('estudiante.estudiante', compact('cursos'));
    }
    public function perfilEstudiante() {
    return view('estudiante.perfil');
}

public function actualizarPerfilEstudiante(Request $request) {
    $usuario = Auth::user();
    $usuario->nombre = $request->nombre;
    $usuario->apellidos = $request->apellidos;
    $usuario->save();

    return redirect()->route('estudiante.perfil')->with('success', 'Perfil actualizado correctamente');
}

}
