<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Suscripcion;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class HomeAdminController extends Controller
{
    /**
     * Dashboard del admin
     */
    public function index()
    {
        $usuarios = Usuario::all();

        $cursos = Curso::with(['profesor:id,nombre'])
                   ->latest('fecha_creacion')
                   ->get();

        $categorias = Categoria::withCount('cursos')->get();

        // Se envían todas las variables a la vista del dashboard
        return view('layouts.admin', compact('usuarios', 'cursos', 'categorias'));
    }

    /**
     * Listar solo los usuarios en otra página
     */
    public function listarUsuarios()
    {
        $usuarios = Usuario::all();
        return view('admin.usuarios', compact('usuarios')); 
        // Nota: crea la vista resources/views/admin/usuarios.blade.php
    }
    public function cambiarRol(Request $request, Usuario $usuario)
{
    $request->validate([
        'rol_id' => 'required|integer|in:1,2,3',
    ]);

    $usuario->idr = $request->rol_id;
    $usuario->save();

    return back()->with('success', 'Rol actualizado correctamente.');
}

public function eliminarUsuario(Usuario $usuario)
{
    $usuario->delete();
    return back()->with('success', 'Usuario eliminado correctamente.');
}
public function perfilEstudiante() {
    return view('admin.perfiladmin');
}

public function actualizarPerfilEstudiante(Request $request) {
    $usuario = Auth::user();
    $usuario->nombre = $request->nombre;
    $usuario->apellidos = $request->apellidos;
    $usuario->save();

    return redirect()->route('admin.perfiladmin')->with('success', 'Perfil actualizado correctamente');
}

}
