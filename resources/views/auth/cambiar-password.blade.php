@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100 max-w-xs">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4">Cambiar Contraseña</h3>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <!-- Token oculto desde sesión -->
                    <input type="hidden" name="token" value="{{ $token }}">


                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cambiar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
