@extends('layouts.app')

@section('title', 'Agregar Cita')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="mb-4 text-center">Agregar Cita</h2>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="idMascota" class="form-label">Mascota</label>
          <select class="form-select" id="idMascota" name="idMascota" required>
            <option value="">Seleccione una mascota</option>
            @foreach($pets as $pet)
              <option value="{{ $pet['id'] }}" {{ old('idMascota') == $pet['id'] ? 'selected':'' }}>
                {{ $pet['nombre_completo'] }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="idServicio" class="form-label">Servicio</label>
          <select class="form-select" id="idServicio" name="idServicio" required>
            <option value="">Seleccione un servicio</option>
            @foreach($services as $svc)
              <option value="{{ $svc['id'] }}" {{ old('idServicio') == $svc['id'] ? 'selected':'' }}>
                {{ $svc['nombre'] }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="fecha" class="form-label">Fecha</label>
          <input type="date"
                 class="form-control"
                 id="fecha"
                 name="fecha"
                 value="{{ old('fecha') }}"
                 required>
        </div>

        <div class="mb-3">
          <label for="hora" class="form-label">Hora</label>
          <input type="time"
                 class="form-control"
                 id="hora"
                 name="hora"
                 value="{{ old('hora') }}"
                 required>
        </div>

        <div class="mb-3">
          <label for="descripcion" class="form-label">Descripción</label>
          <textarea class="form-control"
                    id="descripcion"
                    name="descripcion"
                    rows="3"
                    required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('appointments.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection