@extends('layouts.app')

@section('title', 'Inicio de sesión')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Iniciar sesión</h2>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="/login" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Introduce tu contraseña" required>
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                </div>
                <div class="text-center">
                    <p>¿No tienes cuenta? <a href="/signin">Regístrate</a></p>
                    <p><a href="/password_recover">¿Olvidaste tu contraseña?</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection