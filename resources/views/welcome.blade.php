@extends('layouts.app')

@section('title', 'Bienvenido')

@section('content')
<h1>Sistema de Autenticación</h1>
<p>Valoracion1 - Administracion de Base de Datos I</p>
<p>
    Sistema completo de registro y autenticación de usuarios con
    verificación por correo electrónico y recuperación de contraseña.
</p>
<a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
@endsection
