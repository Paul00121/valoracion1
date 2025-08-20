@extends('layouts.main')

@section('title', 'Mis Cursos')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Mis Cursos</h2>

    <!-- Cursos que CREÉ (profesor) -->
    @if($cursosCreados->isNotEmpty())
        <h4>Cursos creados por mí</h4>
        <div class="row">
            @foreach($cursosCreados as $curso)
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $curso->titulo }}</h5>
                            <p class="text-muted">{{ Str::limit($curso->descripcion ?? '', 80) }}</p>
                            <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-sm btn-outline-primary mt-auto">Editar</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Cursos que COMPRÉ (estudiante) -->
    @if($cursosComprados->isNotEmpty())
        <h4 class="mt-4">Cursos comprados</h4>
        <div class="row">
            @foreach($cursosComprados as $curso)
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $curso->titulo }}</h5>
                            <p class="text-muted">{{ Str::limit($curso->descripcion ?? '', 80) }}</p>
                            <a href="{{ route('cursos.show', $curso) }}" class="btn btn-sm btn-outline-success mt-auto">Ver curso</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($cursosCreados->isEmpty() && $cursosComprados->isEmpty())
        <div class="alert alert-info text-center mt-4">Aún no tienes cursos.</div>
    @endif
</div>
@endsection
