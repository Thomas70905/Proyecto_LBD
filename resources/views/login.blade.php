@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="auth-container">
    <h2 class="text-center mb-4">
        <i class="fas fa-user-circle me-2"></i>Iniciar Sesión
    </h2>

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-2"></i>Correo Electrónico
            </label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-2"></i>Contraseña
            </label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('password.request') }}" class="text-decoration-none">
                <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
            </a>
        </div>

        <button type="submit" class="btn btn-primary mb-4 w-100">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
        </button>

        <div class="text-center">
            <p class="mb-3 text-muted">¿No tienes una cuenta?</p>
            <a href="{{ route('signin.form') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-user-plus me-2"></i>Registrarse
            </a>
        </div>
    </form>
</div>
@endsection