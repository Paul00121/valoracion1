@extends('layouts.app')

@section('title', 'Verificar Código')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="w-100 max-w-xs">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-4">Verificar Código</h3>
                <form method="POST" action="{{ route('password.verify.code') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Verificar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
