<?php

namespace App\Http\Controllers;

use App\Models\Sesiones_activas;
use App\Models\SesionesActivas;
use Illuminate\Http\Request;

class Sesiones_activasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    return SesionesActivas ::with('usuario')->get();
}

public function destroy(SesionesActivas $sesionActiva)
{
    $sesionActiva->delete();
    return response()->noContent();
}
}
