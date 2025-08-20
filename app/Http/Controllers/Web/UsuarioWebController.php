<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Recuperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioWebController extends Controller
{
    /* -------------------------------------------------
     | 1. PERFIL DEL USUARIO
     * -------------------------------------------------*/
    public function perfil()
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user()->load('plan', 'rol');
        return view('usuario.perfil', compact('usuario'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();
        $usuario->update([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
        ]);

        session(['usuario' => $usuario->nombre]);
        return redirect()->route('perfil')->with('success', 'Perfil actualizado.');
    }

    /* -------------------------------------------------
     | 2. FORMULARIO DE CAMBIO DE CONTRASEÑA
     * -------------------------------------------------*/
    public function cambiarPasswordForm()
    {
        return view('usuario.cambiar-password');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        if (!Hash::check($request->current_password, $usuario->passwd)) {
            return back()->withErrors(['current_password' => 'Contraseña actual incorrecta']);
        }

        $usuario->update(['passwd' => bcrypt($request->password)]);
        return redirect()->route('perfil')->with('success', 'Contraseña actualizada.');
    }
}