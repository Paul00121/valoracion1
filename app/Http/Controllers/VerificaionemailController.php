<?php

namespace App\Http\Controllers;

use App\Models\Verificacionemail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificaionemailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function generarToken(Request $request)
    {
        $request->validate(['usuario_id' => 'required|exists:usuarios,idu']);
    
        $token = Str::random(64);
    
        VerificacionEmail::updateOrCreate(
            ['usuario_id' => $request->usuario_id],
            ['token' => $token, 'expiracion' => now()->addDay()]
        );
    
        return response()->json(['token' => $token]);
    }

    public function verificar($token)
    {
        $verificacion = VerificacionEmail::where('token', $token)
                      ->where('expiracion', '>', now())
                      ->firstOrFail();
    
        $verificacion->usuario->update(['verificado' => true]);
        $verificacion->delete();
    
        return response()->json(['mensaje' => 'Email verificado']);
    }
}
