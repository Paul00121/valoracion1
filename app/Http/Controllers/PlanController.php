<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Plan::all();
    }

    public function store(Request $request)
    {
        return Plan::create($request->validate([
            'nombre' => 'required|string|max:50',
            'sesiones_permitidas' => 'required|integer',
            'precio' => 'required|numeric',
            'descuento_cursos' => 'required|numeric'
        ]));
    }

    public function show(Plan $plan)
    {
        return $plan->load('usuarios');
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($request->validate([
            'nombre' => 'sometimes|string|max:50',
            'sesiones_permitidas' => 'sometimes|integer',
            'precio' => 'sometimes|numeric'
        ]));
        return $plan;
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->noContent();
    }
}
