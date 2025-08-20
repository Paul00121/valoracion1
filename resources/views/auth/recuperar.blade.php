@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100 max-w-xs">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4">Recuperar Contraseña</h3>
                <form method="POST" action="{{ route('password.request') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" name="correo" id="correo" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enviar Código</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection