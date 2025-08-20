<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Clase;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursoWebController extends Controller
{
    /* -------------------------------------------------
     | 1. EXPLORAR CURSOS CON FILTROS
     * -------------------------------------------------*/
    public function explorar(Request $request)
    {
        $categorias = Categoria::all(['idc', 'nombre']);

        $query = Curso::with(['categoria', 'profesor']);

        if ($request->filled('categoria')) {
            $query->where('id_categoria', $request->categoria);
        }

        if ($request->filled('busqueda')) {
            $query->where('titulo', 'like', '%' . $request->busqueda . '%');
        }

        switch ($request->orden) {
            case 'puntuacion':   $query->orderBy('puntuacion', 'desc'); break;
            case 'precio_asc':   $query->orderBy('precio', 'asc');  break;
            case 'precio_desc':  $query->orderBy('precio', 'desc'); break;
            default:             $query->orderBy('fecha_creacion', 'desc');
        }

        $cursos = $query->paginate(9)->withQueryString();
        return view('cursos.explorar', compact('cursos', 'categorias'));
    }

    /* -------------------------------------------------
     | 2. DETALLE
     * -------------------------------------------------*/
    public function show(Curso $curso)
    {
        $usuario = Auth::user();

        $tieneAcceso = $curso->es_gratis
            ?: Compra::where('idusuario', $usuario?->idu ?? 0)
                     ->where('idcurso', $curso->idcurso)
                     ->exists();

        $curso->load([
            'clases:idclase,idcurso,titulo,tipo,duracion,orden',
            'resenas.usuario:id,nombre',
            'profesor:id,nombre'
        ]);

        $duracionTotal = $curso->clases->sum('duracion') ?: 0;

        return view('cursos.show', compact('curso', 'tieneAcceso', 'duracionTotal'));
    }

    /* -------------------------------------------------
     | 3. CREAR CURSO (solo profesor / suscripción)
     * -------------------------------------------------*/
    public function create()
    {
        abort_unless(Auth::user()->suscripciones()->where('estado', 'activa')->exists(), 403);
        $categorias = Categoria::all();
        return view('cursos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->suscripciones()->where('estado', 'activa')->exists(), 403);

        $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'required|string',
            'precio'       => 'required|numeric|min:0',
            'id_categoria' => 'nullable|exists:categorias,idc',
            'es_gratis'    => 'boolean',
        ]);

        $curso = Auth::user()->cursosComoProfesor()->create($request->all());
        return redirect()->route('cursos.edit', $curso)->with('success', 'Curso creado.');
    }

    /* -------------------------------------------------
     | 4. EDITAR CURSO
     * -------------------------------------------------*/
    public function edit(Curso $curso)
    {
        abort_if($curso->id_profesor !== Auth::id(), 403);
        $categorias = Categoria::all();
        $clases     = $curso->clases()->orderBy('orden')->get();
        return view('cursos.edit', compact('curso', 'categorias', 'clases'));
    }

    public function update(Request $request, Curso $curso)
    {
        abort_if($curso->id_profesor !== Auth::id(), 403);
        $curso->update($request->only([
            'titulo', 'descripcion', 'precio', 'id_categoria', 'es_gratis'
        ]));
        return back()->with('success', 'Curso actualizado.');
    }

    /* -------------------------------------------------
     | 5. ELIMINAR CLASE
     * -------------------------------------------------*/
    public function destroyClase(Request $request)
    {
        $clase = Clase::findOrFail($request->id);
        abort_unless(
            Auth::id() === $clase->curso->id_profesor || Auth::user()->idr === 1,
            403
        );
        $id_curso = $clase->idcurso;
        $clase->delete();
        return redirect()->route('cursos.edit', $id_curso)->with('success', 'Clase eliminada.');
    }
}