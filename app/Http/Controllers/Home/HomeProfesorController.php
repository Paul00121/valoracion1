<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeProfesorController extends Controller
{
    // Dashboard del profesor (muestra lista de estudiantes)
    public function index()
    {
        // Obtener solo los usuarios que son estudiantes
        $estudiantes = Usuario::where('idr', 3)->get(); 

        // Pasar a la vista del dashboard del profesor
        return view('profesor.profesor', compact('estudiantes'));
    }

    // Perfil profesor (solo muestra los datos del profesor)
    public function perfilProfesor()
    {
        $usuario = Auth::user(); // si quieres mostrar info del profesor en la vista
        return view('profesor.perfil', compact('usuario'));
    }

    // Actualizar perfil profesor
    public function actualizarPerfilProfesor(Request $request)
    {
        $usuario = Auth::user();
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->save();

        return redirect()->route('profesor.perfil')->with('success', 'Perfil actualizado correctamente');
    }
}
