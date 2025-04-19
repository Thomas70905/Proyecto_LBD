@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container mt-5 px-3">
  <div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6">
      <h2 class="mb-4 text-center">Cambiar Contraseña</h2>

      <!-- Validation Errors -->
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="password" class="form-label">Nueva Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Introduce tu nueva contraseña"
            required
          >
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="password_confirmation"
            name="password_confirmation"
            placeholder="Repite tu nueva contraseña"
            required
          >
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            Actualizar Contraseña
          </button>
        </div>

        <div class="text-center mt-3">
          <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection