<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Curso;
use App\Models\Compra;
use App\Models\Suscripcion;
use App\Models\TransaccionPaypal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;

class SuscripcionWebController extends Controller
{
    /* -----------------------------------------------------------------
     |  1. MOSTRAR PLANES
     * -----------------------------------------------------------------*/
    public function elegirPlan()
    {
        $planes = Plan::all();
        return view('suscripciones.elegir-plan', compact('planes'));
    }

    /* -----------------------------------------------------------------
     |  2. CREAR PAGO DE SUSCRIPCIÓN
     * -----------------------------------------------------------------*/
    public function procesarPagoPlan(Request $request)
    {
        $plan = Plan::findOrFail($request->id_plan);

        $payment = $this->crearPagoPayPal(
            $plan->precio,
            'Suscripción ' . $plan->nombre,
            route('suscripcion.confirmar-plan'),
            route('suscripcion.cancelar')
        );

        // Guardar datos en sesión
        session(['pago_pendiente' => [
            'id_payment' => $payment->getId(),
            'id_plan'    => $plan->idp,
            'monto'      => $plan->precio,
            'tipo'       => 'suscripcion',
        ]]);

        return redirect($payment->getApprovalLink());
    }

    /* -----------------------------------------------------------------
     |  3. CREAR PAGO DE CURSO
     * -----------------------------------------------------------------*/
    public function procesarPagoCurso(Request $request)
    {
        $curso   = Curso::findOrFail($request->id_curso);
        $usuario = Auth::user();

        // Verificar que no lo haya comprado
        if ($usuario->compras()->where('idcurso', $curso->idcurso)->exists()) {
            return redirect()->route('curso.show', $curso)->with('info', 'Ya tienes este curso.');
        }

        // Precio con descuento si tiene suscripción activa
        $descuento  = $usuario->suscripciones()
                              ->where('estado', 'activa')
                              ->where('fecha_fin', '>', now())
                              ->join('planes', 'planes.idp', '=', 'suscripciones.idplan')
                              ->value('planes.descuento_cursos') ?? 0;

        $precioFinal = $descuento > 0
            ? $curso->precio * (1 - $descuento / 100)
            : $curso->precio;

        $payment = $this->crearPagoPayPal(
            $precioFinal,
            'Compra: ' . $curso->titulo,
            route('suscripcion.confirmar-curso'),
            route('suscripcion.cancelar')
        );

        session(['pago_pendiente_curso' => [
            'id_payment'      => $payment->getId(),
            'id_curso'        => $curso->idcurso,
            'monto'           => $precioFinal,
            'descuento'       => $descuento,
            'precio_original' => $curso->precio,
            'tipo'            => 'compra_curso',
        ]]);

        return redirect($payment->getApprovalLink());
    }

    /* -----------------------------------------------------------------
     |  4. CONFIRMAR PAGO (SUSCRIPCIÓN)
     * -----------------------------------------------------------------*/
    public function confirmarPlan(Request $request)
    {
        $pendiente = session('pago_pendiente');
        abort_if(!$pendiente, 404);

        $payment = $this->ejecutarPago($request->paymentId, $request->PayerID);

        // Crear suscripción
        Suscripcion::create([
            'idusuario'    => Auth::id(),
            'idplan'       => $pendiente['id_plan'],
            'fecha_inicio' => now(),
            'fecha_fin'    => now()->addMonth(),
            'estado'       => 'activa',
        ]);

        TransaccionPaypal::create([
            'idusuario'      => Auth::id(),
            'tipo'           => 'suscripcion',
            'idsuscripcion'  => Suscripcion::latest()->first()->idsuscripcion,
            'id_paypal'      => $pendiente['id_payment'],
            'estado'         => 'completado',
            'monto'          => $pendiente['monto'],
            'datos_completos'=> json_encode($payment),
        ]);

        session()->forget('pago_pendiente');
        return redirect()->route('home')->with('success', '¡Suscripción activada!');
    }

    /* -----------------------------------------------------------------
     |  5. CONFIRMAR PAGO (CURSO)
     * -----------------------------------------------------------------*/
    public function confirmarCurso(Request $request)
    {
        $pendiente = session('pago_pendiente_curso');
        abort_if(!$pendiente, 404);

        $payment = $this->ejecutarPago($request->paymentId, $request->PayerID);

        // Calcular comisión y pago al profesor
        $comision      = $pendiente['descuento'] > 0 ? 10 : 30;
        $pagoProfesor  = $pendiente['monto'] * (1 - $comision / 100);

        Compra::create([
            'idusuario'        => Auth::id(),
            'idcurso'          => $pendiente['id_curso'],
            'monto_pagado'     => $pendiente['monto'],
            'descuento_aplicado'=> $pendiente['descuento'],
            'comision_plataforma' => $comision,
            'pago_profesor'    => $pagoProfesor,
        ]);

        TransaccionPaypal::create([
            'idusuario'   => Auth::id(),
            'tipo'        => 'compra_curso',
            'idcompra'    => Compra::latest()->first()->idcompra,
            'id_paypal'   => $pendiente['id_payment'],
            'estado'      => 'completado',
            'monto'       => $pendiente['monto'],
            'datos_completos' => json_encode($payment),
        ]);

        session()->forget('pago_pendiente_curso');
        return redirect()->route('curso.show', $pendiente['id_curso'])
                         ->with('success', 'Curso comprado exitosamente');
    }

    /* -----------------------------------------------------------------
     |  6. CANCELAR / ERROR
     * -----------------------------------------------------------------*/
    public function cancelar()
    {
        session()->forget(['pago_pendiente', 'pago_pendiente_curso']);
        return back()->with('error', 'Pago cancelado o fallido.');
    }

    /* -----------------------------------------------------------------
     |  HELPERS INTERNOS
     * -----------------------------------------------------------------*/
    private function crearPagoPayPal(float $monto, string $descripcion, string $returnUrl, string $cancelUrl)
{
    $apiContext = $this->apiContext();

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    // FORZAR a float y formatear como string con 2 decimales
    $monto = number_format((float) $monto, 2, '.', '');

    $amount = new Amount();
    $amount->setTotal($monto) // ahora es string "100.00"
       ->setCurrency('USD');


    $transaction = new Transaction();
    $transaction->setAmount($amount)
                ->setDescription($descripcion);

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($returnUrl)
                 ->setCancelUrl($cancelUrl);

    $payment = new Payment();
    $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

    try {
        $payment->create($apiContext);
    } catch (PayPalConnectionException $ex) {
        Log::error('PayPal error', ['message' => $ex->getData()]);
        abort(500, 'Error conectando con PayPal');
    }

    return $payment;
}


    private function ejecutarPago(string $paymentId, string $payerId)
    {
        $apiContext = $this->apiContext();
        $payment    = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        return $payment->execute($execution, $apiContext);
    }

     private function apiContext(): ApiContext
    {
        return new ApiContext(
            new OAuthTokenCredential(
                'AQAB86KMxhNOiOk-dv6HjJJYeeyqlfpFIwBwU1O2tXccHLjZ7ILQd6fTr50xtmsMptjhQXh-pN3LkAb8',
                'ECveZSZJJyOO98RlOUC0KkjdzRFNUk5PUSH0trKHatcnq0-25XZjWCuvS1SPVE9t8_GwqSUzxmaH0bA2'
            )
        );
    }
}