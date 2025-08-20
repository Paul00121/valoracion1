@extends('layouts.app')

@section('title', 'Inicio de Sesión')

@section('content')
<div class="card shadow-lg p-4 text-dark bg-white" style="max-width: 400px; width: 100%">
    <h1 class="text-center mb-3">Inicio de Sesión</h1>
    <p class="text-center text-muted mb-4">Bienvenido, por favor ingrese sus datos</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input type="email" class="form-control" id="email" name="correo" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="passwd" required>
        </div>
        <div class="mb-3 text-end">
            <a href="{{ route('password.recover') }}" class="link-secondary">¿Olvidó su contraseña?</a>
        </div>
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </div>
        <div class="text-center">
            <span>¿No tienes cuenta? <a href="{{ route('register') }}">Registrarse</a></span>
        </div>
    </form>
</div>
@endsection