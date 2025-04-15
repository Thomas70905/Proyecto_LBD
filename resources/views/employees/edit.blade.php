@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Empleado</h2>

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

            <form action="{{ route('employees.update', $veterinario->id ?? 0) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombreCompleto" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" 
                           value="{{ old('nombreCompleto', $veterinario->nombrecompleto ?? '') }}" 
                           placeholder="Introduce el nombre completo" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $veterinario->email ?? '') }}" 
                           placeholder="Introduce el correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" 
                           value="{{ old('fechaInicio', $veterinario->fechainicio ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" 
                           value="{{ old('telefono', $veterinario->telefono ?? '') }}" 
                           placeholder="Introduce el teléfono" required>
                </div>
                <div class="mb-3">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="especialidad" name="especialidad" 
                           value="{{ old('especialidad', $veterinario->especialidad ?? '') }}" 
                           placeholder="Introduce la especialidad" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection