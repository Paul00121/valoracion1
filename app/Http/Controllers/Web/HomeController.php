<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Suscripcion;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Cursos destacados
        $cursos = Curso::with(['profesor:id,nombre'])
                   ->latest('fecha_creacion')
                   ->limit(6)
                   ->get();

        // 2. Suscripción activa
        $suscripcion = Suscripcion::with('plan')
                              ->where('idusuario', Auth::id())
                              ->where('estado', 'activa')
                              ->where('fecha_fin', '>', now())
                              ->first();

        // 3. Categorías con cantidad de cursos
        $categorias = \App\Models\Categoria::withCount('cursos')->get();

        return view('home', compact('cursos', 'suscripcion', 'categorias'));
    }
}