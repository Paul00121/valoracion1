@extends('layouts.profesor')

@section('title', 'Mi Perfil - Profesor')

@section('content')
<div class="container mt-5" style="max-width: 600px">

    <div class="card shadow">
        {{-- Encabezado azul --}}
        <div class="card-header text-white" style="background: linear-gradient(90deg, #4e73df, #1cc88a);">
            <h3 class="mb-0">Mi Perfil</h3>
        </div>

        <div class="card-body">

            {{-- Alertas de éxito --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profesor.perfil.actualizar') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="{{ Auth::user()->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" value="{{ Auth::user()->apellidos }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->correo }}" readonly>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">Actualizar perfil</button>
                    <a class="btn btn-outline-secondary" href="{{ route('password.recover') }}">Cambiar Contraseña</a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
