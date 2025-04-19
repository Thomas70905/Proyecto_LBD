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
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text"
                           class="form-control"
                           id="nombre_completo"
                           name="nombre_completo"
                           value="{{ old('nombre_completo', $veterinario->nombre_completo ?? '') }}"
                           placeholder="Introduce el nombre completo"
                           required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email"
                           class="form-control"
                           id="email"
                           name="email"
                           value="{{ old('email', $veterinario->email ?? '') }}"
                           placeholder="Introduce el correo electrónico"
                           required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text"
                           class="form-control"
                           id="telefono"
                           name="telefono"
                           value="{{ old('telefono', $veterinario->telefono ?? '') }}"
                           placeholder="Introduce el teléfono"
                           required>
                </div>

                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select id="rol"
                            name="rol"
                            class="form-control"
                            required>
                        <option value="veterinario"
                            {{ old('rol', $veterinario->rol ?? '') === 'veterinario' ? 'selected' : '' }}>
                            Veterinario
                        </option>
                        <option value="administrador"
                            {{ old('rol', $veterinario->rol ?? '') === 'administrador' ? 'selected' : '' }}>
                            Administrador
                        </option>
                    </select>
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