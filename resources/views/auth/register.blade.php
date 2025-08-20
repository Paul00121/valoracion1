@extends('layouts.app')

@section('title', 'Registro de Cuenta')

@section('content')
<div class="card shadow-lg p-4" style="max-width: 600px; width: 100%">
    <h1 class="text-center mb-4">Crear una cuenta</h1>

    {{-- Muestra errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        {{-- Nombre --}}
<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
</div>

{{-- Apellidos --}}
<div class="mb-3">
    <label for="apellidos" class="form-label">Apellidos</label>
    <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
</div>

{{-- Email --}}
<div class="mb-3">
    <label for="correo" class="form-label">Correo electrónico</label>
    <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo') }}" required>
</div>

{{-- Contraseña --}}
<div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input type="password" class="form-control" id="password" name="password" required minlength="8">
</div>

{{-- Confirmar contraseña --}}
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
</div>

        {{-- Botón --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
        </div>

        {{-- Enlace a login --}}
        <div class="text-center mt-3">
            <span>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a></span>
        </div>
    </form>
</div>
@endsection