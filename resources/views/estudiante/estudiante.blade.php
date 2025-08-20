@extends('layouts.estudiante')

@section('title', 'Inicio - Estudiante')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Bienvenido, {{ Auth::user()->nombre }}</h2>
</div>

<p>Aquí podrás ver tus cursos y tu información como estudiante.</p>

@if(isset($cursos) && $cursos->count() > 0)
    <h4>Mis Cursos</h4>
    <div class="row">
        @foreach($cursos as $curso)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $curso->nombre }}</h5>
                        <p class="card-text">Profesor: {{ $curso->profesor->nombre ?? 'N/A' }}</p>
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-sm">Ver Curso</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p>No estás inscrito en ningún curso aún.</p>
@endif
@endsection
