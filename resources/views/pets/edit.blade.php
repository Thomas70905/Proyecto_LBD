@extends('layouts.app')

@section('title', 'Editar Mascota')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Mascota</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pets.update', $pet['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Mascota</label>
                    <input type="text"
                           class="form-control"
                           id="nombre_completo"
                           name="nombre_completo"
                           value="{{ old('nombre_completo', $pet['nombre_completo']) }}"
                           placeholder="Nombre completo de la mascota"
                           required>
                </div>

                <div class="mb-3">
                    <label for="edad" class="form-label">Edad (años)</label>
                    <input type="number"
                           class="form-control"
                           id="edad"
                           name="edad"
                           value="{{ old('edad', $pet['edad']) }}"
                           placeholder="Edad de la mascota"
                           required>
                </div>

                <div class="mb-3">
                    <label for="peso" class="form-label">Peso (kg)</label>
                    <input type="number"
                           step="0.01"
                           class="form-control"
                           id="peso"
                           name="peso"
                           value="{{ old('peso', number_format($pet['peso'], 2)) }}"
                           placeholder="Peso en kilogramos"
                           required>
                </div>

                <div class="mb-3">
                    <label for="raza" class="form-label">Raza</label>
                    <input type="text"
                           class="form-control"
                           id="raza"
                           name="raza"
                           value="{{ old('raza', $pet['raza']) }}"
                           placeholder="Raza de la mascota"
                           required>
                </div>

                <div class="mb-3">
                    <label for="especie" class="form-label">Especie</label>
                    <input type="text"
                           class="form-control"
                           id="especie"
                           name="especie"
                           value="{{ old('especie', $pet['especie']) }}"
                           placeholder="Especie de la mascota"
                           required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('pets.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection