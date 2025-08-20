@extends('layouts.main')

@section('title', 'Elige tu plan')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-3">Elige tu plan</h2>
    <p class="text-center text-muted mb-4">Selecciona el plan que mejor se adapte a tus necesidades</p>

    <div class="row justify-content-center g-4">
        @foreach($planes as $plan)
            <div class="col-md-4">
                <div class="card shadow-sm h-100 border-0 plan-card">
                    @if($plan->nombre === 'Premium')
                        <div class="card-header bg-primary text-white text-center">
                            {{ $plan->nombre }}
                        </div>
                    @else
                        <div class="card-header bg-light text-center">
                            {{ $plan->nombre }}
                        </div>
                    @endif

                    <div class="card-body text-center d-flex flex-column">
                        <h3 class="card-title mb-2">${{ number_format($plan->precio, 2) }}/mes</h3>
                        <p class="card-text mb-2">{{ $plan->sesiones }} sesión(es) simultánea(s)</p>
                        <p class="card-text mb-2">Acceso a cursos gratuitos</p>
                        <p class="card-text mb-2">{{ $plan->descuento_cursos }}% descuento en cursos de pago</p>
                        <p class="card-text mb-4">Creación ilimitada de cursos</p>

                        <form method="POST" action="{{ route('suscripcion.pagar') }}">
                            @csrf
                            <input type="hidden" name="id_plan" value="{{ $plan->idp }}">
                            <button type="submit" class="btn btn-primary w-100 mt-auto">Seleccionar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.plan-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.plan-card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
.card-header {
    font-size: 1.25rem;
    font-weight: 600;
    padding: 1rem;
}
.card-body h3 {
    font-weight: 700;
}
</style>
@endsection
