@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Editar Producto</h2>
            <form action="/inventory/{{ $product->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $product->name ?? 'Producto Ejemplo') }}" 
                           placeholder="Nombre del producto" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control" id="description" name="description" 
                              placeholder="Descripción del producto" required>{{ old('description', $product->description ?? 'Descripción del producto') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Precio</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" 
                           value="{{ old('price', $product->price ?? '0.00') }}" 
                           placeholder="Precio del producto" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" 
                           value="{{ old('stock', $product->stock ?? '0') }}" 
                           placeholder="Cantidad en stock" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="/inventory" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection