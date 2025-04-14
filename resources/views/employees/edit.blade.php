@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Empleado</h2>
            <form action="/employees/{{ $employee->id ?? 0 }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $employee->name ?? 'Nombre Ejemplo') }}" 
                           placeholder="Introduce el nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $employee->email ?? 'ejemplo@correo.com') }}" 
                           placeholder="Introduce el correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Puesto</label>
                    <input type="text" class="form-control" id="position" name="position" 
                           value="{{ old('position', $employee->position ?? 'Puesto') }}" 
                           placeholder="Introduce el puesto" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="/employees" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection