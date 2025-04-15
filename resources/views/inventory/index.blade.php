@extends('layouts.app')

@section('title', 'Administración de Inventario')

@section('content')
<div class="container mt-5">
    <h2>Administración de Inventario</h2>
    <a href="{{ route('inventory.create') }}" class="btn btn-success my-2">Agregar Producto</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Fecha de Caducidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventories as $inventory)
                    <tr>
                        <td>{{ $inventory['id'] }}</td>
                        <td>{{ $inventory['nombreproducto'] }}</td>
                        <td>{{ $inventory['descripcion'] }}</td>
                        <td>${{ number_format($inventory['precio'], 2) }}</td>
                        <td>{{ $inventory['cantidadunidades'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($inventory['fechacaducidad'])->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('inventory.edit', $inventory['id']) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('inventory.destroy', $inventory['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay productos en el inventario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection