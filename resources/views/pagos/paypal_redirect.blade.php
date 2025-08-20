@extends('layouts.app')
@section('content')
<div class="text-center mt-5">
    <h3>Redirigiendo a PayPal...</h3>
    <p>Por favor espera, serás llevado al pago en unos segundos.</p>
    <i class="fas fa-spinner fa-spin fa-2x"></i>

    <!-- Script que envía al usuario a PayPal -->
    <script>
        setTimeout(() => {
            window.location = "{{ $paypalUrl ?? 'https://www.paypal.com' }}";
        }, 2000);
    </script>
</div>
@endsection