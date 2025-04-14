@extends('layouts.app')

@section('title', 'Editar Servicio')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Servicio</h2>
            <form action="/services/{{ $service->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $service->name ?? 'Servicio Ejemplo') }}" 
                           placeholder="Nombre del servicio" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" 
                              placeholder="Descripción del servicio" required>{{ old('description', $service->description ?? 'Descripción del servicio') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duración (minutos)</label>
                    <input type="number" class="form-control" id="duration" name="duration" 
                           value="{{ old('duration', $service->duration ?? 60) }}" 
                           placeholder="Duración en minutos" required>
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label">Costo</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" 
                           value="{{ old('cost', $service->cost ?? '0.00') }}" 
                           placeholder="Costo del servicio" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="/services" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection