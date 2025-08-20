<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;

class CompraWebController extends Controller
{
    /**
     * Muestra:
     * 1. Cursos creados por el usuario logueado (rol profesor)
     * 2. Cursos que ha comprado (rol estudiante)
     */
    public function misCursos()
    {
        // Cursos creados por el usuario actual
        $cursosCreados = Auth::user()->cursosComoProfesor()
                                     ->orderBy('fecha_creacion', 'desc')
                                     ->get();

        // Cursos comprados por el usuario actual
        $cursosComprados = Auth::user()->compras()
                                     ->with('curso.profesor:id,nombre')
                                     ->orderBy('fecha', 'desc')
                                     ->get()
                                     ->pluck('curso');   // devuelve Collection<Curso>

        return view('compras.mis-cursos', compact('cursosCreados', 'cursosComprados'));
    }
}