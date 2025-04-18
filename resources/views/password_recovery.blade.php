@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="container mt-5 px-3">
  <div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6">
      <h2 class="mb-4 text-center">Recuperar Contraseña</h2>

      <!-- Success Message -->
      @if(session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

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

      <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Introduce tu correo"
            required
          >
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            Enviar enlace de recuperación
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