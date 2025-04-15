@extends('layouts.app')

@section('title', 'Agregar Cita')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Agregar Cita</h2>
            <form action="/appointments" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="mascot" class="form-label">Nombre de la Mascota</label>
                    <input type="text" class="form-control" id="mascot" name="mascot" placeholder="Introduce el nombre de la mascota" required>
                </div>
                <div class="mb-3">
                    <label for="service" class="form-label">Servicio</label>
                    <select class="form-select" id="service" name="service" required>
                        <option value="">Seleccione un servicio</option>
                        <option value="Consulta General">Consulta General</option>
                        <option value="Vacunación">Vacunación</option>
                        <option value="Desparasitación">Desparasitación</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Hora</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="/appointments" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection