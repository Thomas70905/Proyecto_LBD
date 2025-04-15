@extends('layouts.app')

@section('title', 'Editar Servicio')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Servicio</h2>

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

            <form action="{{ route('services.update', $service['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="name" 
                           value="{{ old('name', $service['nombre']) }}" 
                           placeholder="Nombre del servicio" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="description" 
                              placeholder="Descripción del servicio" required>{{ old('description', $service['descripcion']) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="duracion" class="form-label">Duración (minutos)</label>
                    <input type="number" class="form-control" id="duracion" name="duration" 
                           value="{{ old('duration', $service['duracion']) }}" 
                           placeholder="Duración en minutos" required>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Costo</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="cost" 
                           value="{{ old('cost', $service['precio']) }}" 
                           placeholder="Costo del servicio" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection