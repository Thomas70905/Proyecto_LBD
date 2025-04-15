@extends('layouts.app')

@section('title', 'Agregar Producto')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Agregar Producto</h2>

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

            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombreProducto" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" 
                           value="{{ old('nombreProducto') }}" 
                           placeholder="Nombre del producto" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" 
                              placeholder="Descripción del producto" required>{{ old('descripcion') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                           value="{{ old('precio') }}" 
                           placeholder="Precio del producto" required>
                </div>
                <div class="mb-3">
                    <label for="cantidadUnidades" class="form-label">Cantidad Unidades</label>
                    <input type="number" class="form-control" id="cantidadUnidades" name="cantidadUnidades" 
                           value="{{ old('cantidadUnidades') }}" 
                           placeholder="Cantidad de unidades disponibles" required>
                </div>
                <div class="mb-3">
                    <label for="fechaCaducidad" class="form-label">Fecha de Caducidad</label>
                    <input type="date" class="form-control" id="fechaCaducidad" name="fechaCaducidad" 
                           value="{{ old('fechaCaducidad') }}" 
                           placeholder="Fecha de caducidad" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection