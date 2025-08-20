<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CursosPro')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .user-avatar { width:32px; height:32px; border-radius:50%; }
        .banner-suscripcion { background:#fff; border:1px solid #dee2e6; border-radius:8px; }
        .categoria-card { background:#fff; border-radius:8px; padding:1.5rem; text-align:center; box-shadow:0 .125rem .25rem rgba(0,0,0,.075); }
        .categoria-icon { font-size:2.5rem; color:#6c5ce7; margin-bottom:.75rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">CursosPro</a>
            <div class="d-flex me-auto">
                <a class="btn btn-warning btn-sm text-dark fw-bold" href="{{ route('planes.seleccionar') }}">
                    <i class="fas fa-star me-1"></i>Obtener Suscripción
                </a>
            </div>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cursos.explorar') }}">Explorar Cursos</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=MP&background=random" class="user-avatar me-1">
                            {{ Auth::user()->nombre ?? 'Usuario' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('perfil') }}">Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="{{ route('compras.mis-cursos') }}">Mis Cursos</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container my-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
