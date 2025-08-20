@extends('layouts.profesor') <!-- si tienes layout -->

@section('title', 'Dashboard - Profesor')

@section('content')
<h2>Bienvenido, {{ Auth::user()->nombre }}</h2>

<p>Lista de estudiantes:</p>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Registrado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($estudiantes as $estudiante)
        <tr>
            <td>{{ $estudiante->id }}</td>
            <td>{{ $estudiante->nombre }}</td>
            <td>{{ $estudiante->apellidos }}</td>
            <td>{{ $estudiante->correo }}</td>
            <td>{{ $estudiante->created_at ? $estudiante->created_at->format('d/m/Y') : 'No definido' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
