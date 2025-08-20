@extends('layouts.main')

@section('title', 'Explorar Cursos')

@section('content')

<h2 class="mb-4">Explorar Cursos</h2>

<!-- Filtros -->
<form method="GET" action="{{ route('cursos.explorar') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar..." value="{{ request('buscar') }}">
    </div>
    <div class="col-md-4">
        <select name="categoria" class="form-select">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->idc }}" {{ request('categoria') == $categoria->idc ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select name="orden" class="form-select">
            <option value="">Ordenar por...</option>
            <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
            <option value="puntuacion" {{ request('orden') == 'puntuacion' ? 'selected' : '' }}>Mejor puntuados</option>
            <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
        </select>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>

<!-- Cursos -->
<div class="row">
    @forelse($cursos as $curso)
        <div class="col-md-4 mb-4">
            <div class="categoria-card h-100 d-flex flex-column">
                <img src="https://via.placeholder.com/400x220?text={{ urlencode($curso->titulo) }}" class="card-img-top rounded" alt="{{ $curso->titulo }}">
                <div class="mt-3 flex-grow-1">
                    <h5>{{ $curso->titulo }}</h5>
                    <p class="text-muted">{{ Str::limit($curso->descripcion, 100) }}</p>
                    <p class="mb-1">
                        <small>Por <strong>{{ $curso->profesor->nombre ?? 'Anónimo' }}</strong></small><br>
                        <small>Puntuación: {{ $curso->puntuacion ?? 0 }} <i class="fas fa-star text-warning"></i></small>
                    </p>
                    @if($curso->es_gratis)
                        <span class="badge bg-success">Gratis</span>
                    @else
                        <span class="badge bg-warning text-dark">${{ number_format($curso->precio, 2) }}</span>
                    @endif
                </div>
                <div class="mt-3">
                    <a href="{{ route('cursos.ver', $curso->idcurso) }}" class="btn btn-outline-primary w-100">Ver Detalles</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle"></i> No se encontraron cursos con los filtros seleccionados.
            </div>
        </div>
    @endforelse
</div>

<!-- Paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $cursos->links() }}
</div>

@endsection
