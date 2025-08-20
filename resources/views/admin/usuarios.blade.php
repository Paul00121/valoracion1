@extends('layouts.admin')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Usuarios</h2>
    <a href="{{ route('home.admin') }}" class="btn btn-secondary">Volver al Dashboard</a>
</div>

<!-- Alertas de éxito -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Registrado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->id }}</td>
            <td>{{ $usuario->nombre }}</td>
            <td>{{ $usuario->apellidos }}</td>
            <td>{{ $usuario->correo }}</td>
            <td>{{ $usuario->rol->nombre ?? 'N/A' }}</td>
            <td>{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : 'No definido' }}</td>
            <td>
                <!-- Cambiar rol -->
                <form method="POST" action="{{ route('admin.usuarios.cambiarRol', $usuario) }}" class="d-inline">
                    @csrf
                    <select name="rol_id" class="form-select form-select-sm d-inline w-auto">
                        <option value="1" {{ $usuario->idr == 1 ? 'selected' : '' }}>Administrador</option>
                        <option value="2" {{ $usuario->idr == 2 ? 'selected' : '' }}>Profesor</option>
                        <option value="3" {{ $usuario->idr == 3 ? 'selected' : '' }}>Estudiante</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
                </form>

                <!-- Eliminar usuario -->
                <form method="POST" action="{{ route('admin.usuarios.eliminar', $usuario) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar a este usuario?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
