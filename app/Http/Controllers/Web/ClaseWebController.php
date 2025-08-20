<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Compra;
use Illuminate\Support\Facades\Auth;

class ClaseWebController extends Controller
{
    public function show(Clase $clase)
    {
        // 1️⃣ Verificar acceso
        $tieneAcceso = $clase->curso->es_gratis
            ?: Compra::where('idusuario', Auth::id())
                     ->where('idcurso', $clase->curso->idcurso)
                     ->exists();

        if (!$tieneAcceso) {
            return redirect()->route('curso.show', $clase->curso)
                             ->with('error', 'Debes comprar el curso para ver esta clase.');
        }

        // 2️⃣ Clases del curso (ordenadas)
        $clasesCurso = $clase->curso->clases()
                                    ->orderBy('orden')
                                    ->get(['idclase', 'titulo']);

        // 3️⃣ Clase anterior y siguiente
        $indiceActual = $clasesCurso->search(fn($c) => $c->idclase === $clase->idclase);
        $anterior = $clasesCurso[$indiceActual - 1] ?? null;
        $siguiente = $clasesCurso[$indiceActual + 1] ?? null;

        return view('clases.show', compact('clase', 'clasesCurso', 'anterior', 'siguiente'));
    }
}