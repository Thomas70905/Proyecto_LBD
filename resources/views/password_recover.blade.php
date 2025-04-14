@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Recuperar contraseña</h2>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="/password/email" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Introduce tu correo electrónico" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
                </div>
                <div class="text-center mt-3">
                    <p><a href="/login">Volver a iniciar sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection