<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Plan;
use App\Models\Recuperacion;
use App\Models\SesionesActivas;
use App\Models\TransaccionPaypal;
use App\Models\VerificacionEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Mail;

class AuthWebController extends Controller
{
    /* -----------------------------------------------------------------
     |  LOGIN
     * -----------------------------------------------------------------*/
    public function loginForm()
{
    return view('auth.login');
}
    public function login(Request $request)
{
    // Validar datos del formulario
    $request->validate([
        'correo' => 'required|email',
        'passwd' => 'required|string',
    ]);

    // Credenciales para Auth
    $credentials = [
        'correo'   => $request->correo,
        'password' => $request->passwd, // Laravel usará getAuthPassword() del modelo
    ];

    // Intentar login
    if (!Auth::attempt($credentials)) {
        return back()->withErrors(['credenciales' => 'Correo o contraseña incorrectos']);
    }

    // Usuario autenticado
    $usuario = Auth::user();

    // Verificar si el correo está confirmado
    if (!$usuario->verificado) {
        Auth::logout();
        return back()->withErrors(['no_verificado' => 'Debes verificar tu correo primero']);
    }

    
    // Redirigir según rol
switch ($usuario->idr) {
    case 1: // Administrador
        return redirect()->route('home.admin');
    case 2: // Profesor
        return redirect()->route('home.profesor');
    case 3: // Estudiante
        return redirect()->route('home.estudiante');
    default:
        Auth::logout();
        return back()->withErrors(['rol' => 'Rol no válido']);
}

}


    /* -----------------------------------------------------------------
     |  REGISTRO
     * -----------------------------------------------------------------*/
    
    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
        'nombre'    => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'correo'    => 'required|email|unique:usuarios,correo',
        'password'  => 'required|string|min:8|confirmed',
    ]);

    $rolPorDefecto = 3; // 3 = Estudiante

    $usuario = Usuario::create([
    'nombre'    => $request->nombre,
    'apellidos' => $request->apellidos,
    'correo'    => $request->correo,
    'passwd'    => bcrypt($request->password),
    'idr'       => $rolPorDefecto,
    'verificado'=> false,
]);
        $token = Str::random(60);
        $verif = VerificacionEmail::create([
            'usuario_id' => $usuario->idu,
            'token'      => $token,
            'expiracion' => now()->addHours(24),
            'usado'      => false,
        ]);

        $enlace = route('email.verify', ['token' => $token]);
        Mail::send('emails.verificacion', ['enlace' => $enlace], function ($message) use ($usuario) {
        $message->to($usuario->correo)
                ->subject('Verifica tu cuenta');
        });

        return redirect()->route('login')->with('success', 'Usuario registrado. Revisa tu correo para verificar.');
    }

    /* ----------------------------------------------------------------- 
    | RECUPERACIÓN DE CONTRASEÑA 
    * -----------------------------------------------------------------*/ 
    public function recuperarForm() 
    { 
        return view('auth.recuperar'); 
    }

    public function enviarCodigo(Request $request)
{
    $request->validate(['correo' => 'required|email|exists:usuarios,correo']);

    $correo = $request->correo;
    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $token  = Str::random(60);

    Recuperacion::updateOrCreate(
        ['correo' => $correo],
        [
            'codigo'     => $codigo,
            'expiracion' => now()->addMinutes(60),
            'token'      => $token,
            'usado'      => false,
        ]
    );

    // Guardar correo y token en sesión
    session([
        'correo_recuperacion' => $correo,
        'token_recuperacion' => $token
    ]);

    try {
        // Crear enlace hacia el formulario de cambio de contraseña
        $enlace = route('password.change.form', ['token' => $token]);


        // Enviar vista Blade con código y enlace
        Mail::send('emails.recuperacion', [
            'codigo' => $codigo,
            'enlace' => $enlace
        ], function ($message) use ($correo) {
            $message->to($correo)
                    ->subject('Recuperación de contraseña');
        });

        return redirect()->route('password.verify.code.form')
                         ->with('success', 'Código enviado a tu correo: '.$correo);

    } catch (\Exception $e) {
        return back()->withErrors(['correo' => 'No se pudo enviar el correo: '.$e->getMessage()]);
    }
}



    public function verificarCodigo(Request $request)
    {
    $request->validate(['codigo' => 'required|digits:6']);

    $correo = session('correo_recuperacion');
    $rec = Recuperacion::where('correo', $correo)
                       ->where('codigo', $request->codigo)
                       ->where('usado', false)
                       ->where('expiracion', '>', now())
                       ->first();

    if (!$rec) {
        return back()->withErrors(['codigo' => 'Código inválido o expirado']);
    }

    // Guardar token en sesión para cambiar contraseña
    session([
        'codigo_verificado' => true,
        'token_recuperacion' => $rec->token
    ]);

    // Redirigir al formulario de cambio de contraseña
    return redirect()->route('password.change.form');
    }

    public function cambiarPassword(Request $request)
    {
    $request->validate([
        'token'    => 'required|exists:recuperacion,token',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $rec = Recuperacion::where('token', $request->token)
                       ->where('usado', false)
                       ->where('expiracion', '>', now())
                       ->firstOrFail();

    Usuario::where('correo', $rec->correo)
           ->update(['passwd' => bcrypt($request->password)]);

    $rec->update(['usado' => true]);

    return redirect()->route('login')->with('success', 'Contraseña actualizada. Ya puedes iniciar sesión.');
    }
    public function cambiarPasswordForm(Request $request)
{
    $token = $request->token ?? session('token_recuperacion'); // toma token de URL o sesión
    return view('auth.cambiar-password', compact('token'));
}



    /* -----------------------------------------------------------------
     |  VERIFICACIÓN DE CORREO
     * -----------------------------------------------------------------*/
    public function verificarEmail($token)
{
    // Buscar el token en la tabla de verificación
    $verif = VerificacionEmail::where('token', $token)
        ->where('usado', false)
        ->where('expiracion', '>', now())
        ->firstOrFail();

    // Marcar al usuario como verificado
    Usuario::where('idu', $verif->usuario_id)->update(['verificado' => true]);

    // Marcar el token como usado
    $verif->update(['usado' => true]);

    return redirect()->route('login')->with('success', 'Correo verificado exitosamente');
}

    
    /* -----------------------------------------------------------------
     |  HELPERS PAYPAL
     * -----------------------------------------------------------------*/
    private function apiContext(): ApiContext
    {
        return new ApiContext(
            new OAuthTokenCredential(
                'AQAB86KMxhNOiOk-dv6HjJJJYeeyqlfpFIwBwU1O2tXccHLjZ7ILQd6fTr50xtmsMptjhQXh-pN3LkAb8',
                'ECveZSZJJJyOOO98RlOUC0KkjdzRFNUk5PUSH0trKHatcnq0-25XZjWCuvS1SPVE9t8_GwqSUzxmaH0bA2'
            )
        );
    }

    public function logout(Request $request)
    {
        SesionesActivas::where('session_id', session('sesion_id'))->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    /* -----------------------------------------------------------------
    |  SELECCIONAR PAGO
    * -----------------------------------------------------------------*/

    public function seleccionarPlan(Request $request, $planId)
{
    $plan = Plan::findOrFail($planId);

    // Crear el contexto de PayPal
    $apiContext = $this->apiContext();

    // Configurar pagador
    $payer = new \PayPal\Api\Payer();
    $payer->setPaymentMethod('paypal');

    // Definir el monto
    $amount = new \PayPal\Api\Amount();
    $amount->setTotal(number_format($plan->precio, 2, '.', ''));
    $amount->setCurrency('USD');

    // Transacción
    $transaction = new \PayPal\Api\Transaction();
    $transaction->setAmount($amount);
    $transaction->setDescription("Suscripción al plan: {$plan->nombre}");

    // Redirecciones
    $redirectUrls = new \PayPal\Api\RedirectUrls();
    $redirectUrls->setReturnUrl(route('paypal.success', ['plan' => $plan->idp]))
                 ->setCancelUrl(route('paypal.cancel'));

    // Crear pago
    $payment = new \PayPal\Api\Payment();
    $payment->setIntent('sale')
        ->setPayer($payer)
        ->setTransactions([$transaction])
        ->setRedirectUrls($redirectUrls);

    try {
        $payment->create($apiContext);

        // Guardar ID de pago en BD si quieres
        session(['paypal_payment_id' => $payment->getId()]);

        // Redirigir al usuario a PayPal
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return redirect()->away($link->getHref());
            }
        }

        return back()->withErrors('No se encontró la URL de aprobación de PayPal');
    } catch (\Exception $e) {
        return back()->withErrors('Error en PayPal: '.$e->getMessage());
    }
}

}
