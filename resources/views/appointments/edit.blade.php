@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Cita</h2>
            <form action="/appointments/{{ $appointment->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="mascot" class="form-label">Nombre de la Mascota</label>
                    <input type="text" class="form-control" id="mascot" name="mascot" 
                           value="{{ old('mascot', $appointment->mascot ?? '') }}" 
                           placeholder="Introduce el nombre de la mascota" required>
                </div>
                <div class="mb-3">
                    <label for="service" class="form-label">Servicio</label>
                    <select class="form-select" id="service" name="service" required>
                        <option value="">Seleccione un servicio</option>
                        <option value="Consulta General" {{ old('service', $appointment->service ?? '') == 'Consulta General' ? 'selected' : '' }}>Consulta General</option>
                        <option value="Vacunación" {{ old('service', $appointment->service ?? '') == 'Vacunación' ? 'selected' : '' }}>Vacunación</option>
                        <option value="Desparasitación" {{ old('service', $appointment->service ?? '') == 'Desparasitación' ? 'selected' : '' }}>Desparasitación</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date" name="date" 
                           value="{{ old('date', $appointment->date ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Hora</label>
                    <input type="time" class="form-control" id="time" name="time" 
                           value="{{ old('time', $appointment->time ?? '') }}" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="/appointments" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection