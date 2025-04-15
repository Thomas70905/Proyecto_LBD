@extends('layouts.app')

@section('title', 'Agregar Empleado')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Agregar Empleado</h2>

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

            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombreCompleto" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" 
                           placeholder="Introduce el nombre completo" value="{{ old('nombreCompleto') }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Introduce el correo electrónico" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" 
                           value="{{ old('fechaInicio') }}" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" 
                           placeholder="Introduce el teléfono" value="{{ old('telefono') }}" required>
                </div>
                <div class="mb-3">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="especialidad" name="especialidad" 
                           placeholder="Introduce la especialidad" value="{{ old('especialidad') }}" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection