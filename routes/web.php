<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Home\HomeAdminController;
use App\Http\Controllers\Home\HomeProfesorController;
use App\Http\Controllers\Home\HomeEstudianteController;
use App\Http\Controllers\Web\{
    AuthWebController,
    CursoWebController,
    ClaseWebController,
    CompraWebController,
    SuscripcionWebController,
    UsuarioWebController,
    HomeController
};

// -------------------------------------------------
// PÁGINA DE BIENVENIDA
// -------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// -------------------------------------------------
// LOGIN RÁPIDO (solo para pruebas locales)
// -------------------------------------------------
Route::get('/test-login', function () {
    $usuario = \App\Models\Usuario::first();
    if ($usuario) {
        \Illuminate\Support\Facades\Auth::login($usuario);
    }
    return redirect('/perfil');
});

// -------------------------------------------------
// AUTENTICACIÓN
// -------------------------------------------------
Route::get('/login', [AuthWebController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);


Route::get('/register',   fn () => view('auth.register'))->name('register');
Route::post('/register',  [AuthWebController::class, 'register']);

Route::post('/logout',    [AuthWebController::class, 'logout'])->name('logout');

// -------------------------------------------------
// RECUPERACIÓN DE CONTRASEÑA
// -------------------------------------------------
Route::get('/recuperar', [AuthWebController::class, 'recuperarForm'])->name('password.recover');
Route::post('/recuperar', [AuthWebController::class, 'enviarCodigo'])->name('password.request');

// VERIFICAR CÓDIGO
Route::get('/verificar-codigo', fn() => view('auth.verificar'))->name('password.verify.code.form');
Route::post('/verificar-codigo', [AuthWebController::class, 'verificarCodigo'])->name('password.verify.code');

// CAMBIAR CONTRASEÑA
Route::get('/cambiar-password', [AuthWebController::class, 'cambiarPasswordForm'])->name('password.change.form');
Route::post('/cambiar-password', [AuthWebController::class, 'cambiarPassword'])->name('password.update');


// VERIFICACIÓN DE EMAIL
Route::get('/verificar-email/{token}', [AuthWebController::class, 'verificarEmail'])->name('email.verify');

// -------------------------------------------------
// PERFIL (solo usuarios autenticados)
// -------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/perfil',        [UsuarioWebController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/editar', [UsuarioWebController::class, 'editar'])->name('perfil.editar');
    Route::put('/perfil',        [UsuarioWebController::class, 'actualizar'])->name('perfil.actualizar');
});

// -------------------------------------------------
// CURSOS
// -------------------------------------------------
Route::get('/explorar-cursos', [CursoWebController::class, 'explorar'])->name('cursos.explorar');
Route::get('/cursos/{curso}', [CursoWebController::class, 'show'])->name('cursos.show');

Route::middleware('auth')->group(function () {
    Route::get('/cursos/crear',           [CursoWebController::class, 'create'])->name('cursos.create');
    Route::post('/cursos',                [CursoWebController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/{curso}/editar',  [CursoWebController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{curso}',         [CursoWebController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/{curso}',      [CursoWebController::class, 'destroy'])->name('cursos.destroy');
});

// -------------------------------------------------
// CLASES
// -------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/cursos/{curso}/clases/{clase}', [ClaseWebController::class, 'ver'])->name('clases.ver');
    Route::delete('/clases/{clase}', [ClaseWebController::class, 'eliminar'])->name('clases.eliminar');
});

// -------------------------------------------------
// COMPRAS / MIS CURSOS
// -------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/mis-cursos', [CompraWebController::class, 'misCursos'])->name('compras.mis-cursos');
});

// -------------------------------------------------
// SUSCRIPCIONES / PLANES
// -------------------------------------------------
Route::get('/planes', [SuscripcionWebController::class, 'elegirPlan'])->name('planes.seleccionar');

Route::middleware('auth')->group(function () {
    Route::post('/planes/pagar',            [SuscripcionWebController::class, 'procesarPagoPlan'])->name('suscripcion.pagar');
    Route::get('/cursos/{curso}/pagar',     [SuscripcionWebController::class, 'procesarPagoCurso'])->name('suscripcion.pagar-curso');
    Route::get('/confirmar-pago-plan',      [SuscripcionWebController::class, 'confirmarPlan'])->name('suscripcion.confirmar-plan');
    Route::get('/confirmar-pago-curso',     [SuscripcionWebController::class, 'confirmarCurso'])->name('suscripcion.confirmar-curso');
    Route::get('/cancelar-pago',            [SuscripcionWebController::class, 'cancelar'])->name('suscripcion.cancelar');
});

// -------------------------------------------------
// HOME / DASHBOARD
// -------------------------------------------------
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// -------------------------------------------------
// TEST CORREO
// -------------------------------------------------
Route::get('/test-mail', function () {
    try {
        Mail::raw('Este es un correo de prueba desde Laravel 🚀', function ($message) {
            $message->to('tu_correo_destino@gmail.com')
                    ->subject('Prueba de correo');
        });

        return '✅ Correo enviado correctamente';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

// Ruta para admin
Route::middleware(['auth'])->group(function () {
    // Dashboard admin
    Route::get('/admin', [HomeAdminController::class, 'index'])->name('home.admin');

// Listar usuarios
Route::get('/admin/usuarios', [HomeAdminController::class, 'listarUsuarios'])
         ->name('admin.usuarios.listar');

// Cambiar rol
Route::post('/admin/usuarios/{usuario}/rol', [HomeAdminController::class, 'cambiarRol'])
    ->name('admin.usuarios.cambiarRol');

// Eliminar usuario
Route::delete('/admin/usuarios/{usuario}', [HomeAdminController::class, 'eliminarUsuario'])
    ->name('admin.usuarios.eliminar');

Route::middleware(['auth'])->group(function () {
    // Perfil admin
    Route::get('/admin/perfil', function () {
        return view('admin.perfiladmin');
    })->name('admin.perfil');
});

});

// Profesor
Route::middleware('auth')->group(function () {
    // Inicio del profesor (dashboard)
    Route::get('/profesor', [HomeProfesorController::class, 'index'])->name('home.profesor');

    // Listar solo estudiantes
    Route::get('/profesor/profesor', [HomeProfesorController::class, 'listarEstudiantes'])
         ->name('profesor.profesor');

    // Perfil profesor
    Route::get('/profesor/perfil', [HomeProfesorController::class, 'perfilProfesor'])
         ->name('profesor.perfil');

    // Actualizar perfil profesor
    Route::put('/profesor/perfil', [HomeProfesorController::class, 'actualizarPerfilProfesor'])
         ->name('profesor.perfil.actualizar');

});



// Estudiante
Route::middleware('auth')->group(function () {
    Route::get('/estudiante', [HomeEstudianteController::class, 'index'])->name('home.estudiante');

    // Perfil estudiante
    Route::get('/estudiante/perfil', function () {
        return view('estudiante.perfil');
    })->name('estudiante.perfil');

    // Actualizar perfil estudiante
    Route::put('/estudiante/perfil', [HomeEstudianteController::class, 'actualizarPerfilEstudiante'])
         ->name('estudiante.perfil.actualizar');
});
// PERFIL (solo usuarios autenticados)
Route::middleware('auth')->group(function () {
    // Perfil general (para todos los roles)
    Route::get('/perfil',        [UsuarioWebController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/editar', [UsuarioWebController::class, 'editar'])->name('perfil.editar');
    Route::put('/perfil',        [UsuarioWebController::class, 'actualizar'])->name('perfil.actualizar');

});

