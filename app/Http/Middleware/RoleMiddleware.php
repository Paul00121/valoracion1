<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        // Validación según idr
        if ($role == 'Administrador' && $usuario->idr != 1) abort(403);
        if ($role == 'Profesor' && $usuario->idr != 2) abort(403);
        if ($role == 'Estudiante' && $usuario->idr != 3) abort(403);

        return $next($request);
    }
}
