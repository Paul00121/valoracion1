@extends('layouts.main')

@section('title', 'Inicio')

@section('content')
<!-- Banner de suscripción -->
<div class="p-4 banner-suscripcion text-center">
    <h4 class="mb-2">Obtén una suscripción para acceder a los cursos</h4>
    <p class="mb-3">
        Con nuestra suscripción premium podrás acceder a todos los cursos con un <strong>20 % de descuento</strong>
        y en hasta <strong>3 dispositivos simultáneos</strong>.
    </p>
    <a href="{{ route('planes.seleccionar') }}" class="btn btn-primary btn-lg">Ver Planes</a>
</div>

<!-- Cursos Destacados -->
<div class="my-5">
    <h3 class="mb-4">Cursos Destacados</h3>
    <div class="row">
        <div class="col-md-4 mb-4"><div class="categoria-card"><i class="fas fa-code categoria-icon"></i><h5>Programación</h5><span class="badge bg-secondary">8</span></div></div>
        <div class="col-md-4 mb-4"><div class="categoria-card"><i class="fas fa-palette categoria-icon"></i><h5>Diseño</h5><span class="badge bg-secondary">8</span></div></div>
        <div class="col-md-4 mb-4"><div class="categoria-card"><i class="fas fa-chart-line categoria-icon"></i><h5>Negocios</h5><span class="badge bg-secondary">5</span></div></div>
    </div>
</div>
@endsection
