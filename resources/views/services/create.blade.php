@extends('layouts.app')

@section('title', 'Agregar Servicio')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Agregar Servicio</h2>
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del servicio" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Descripción del servicio" required>{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="duration" class="form-label">Duración (minutos)</label>
                    <input type="number" class="form-control" id="duration" name="duration" placeholder="Duración en minutos" value="{{ old('duration') }}" required>
                </div>
                <div class="mb-3">
                    <label for="cost" class="form-label">Costo</label>
                    <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="Costo del servicio" value="{{ old('cost') }}" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection