@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="container mt-5 px-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Crear una cuenta</h2>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('signin.register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                           value="{{ old('nombre_completo') }}" 
                           placeholder="Introduce tu nombre completo" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Introduce tu correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Introduce una contraseña" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                           placeholder="Confirma tu contraseña" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" 
                           value="{{ old('telefono') }}" 
                           placeholder="Introduce tu teléfono" required>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" 
                           value="{{ old('direccion') }}" 
                           placeholder="Introduce tu dirección" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </div>
                <div class="text-center mt-3">
                    <p>¿Ya tienes cuenta? <a href="/login">Inicia sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection