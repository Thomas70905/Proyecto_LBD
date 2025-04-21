@extends('layouts.app')

@section('title', 'Administración de Inventario')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Administración de Inventario</h2>
        <a href="{{ route('inventory.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Agregar Producto
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 20%">Nombre</th>
                            <th style="width: 25%">Descripción</th>
                            <th class="text-end" style="width: 10%">Precio</th>
                            <th class="text-center" style="width: 15%">Cantidad</th>
                            <th class="text-center" style="width: 15%">Fecha Caducidad</th>
                            <th class="text-center" style="width: 10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $inventory)
                            <tr>
                                <td class="text-center">{{ $inventory['id'] }}</td>
                                <td>
                                    <strong>{{ $inventory['nombreproducto'] }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $inventory['descripcion'] }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">${{ number_format($inventory['precio'], 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $inventory['cantidadunidades'] > 10 ? 'success' : ($inventory['cantidadunidades'] > 5 ? 'warning' : 'danger') }}">
                                        {{ $inventory['cantidadunidades'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ \Carbon\Carbon::parse($inventory['fechacaducidad'])->isFuture() ? 'info' : 'danger' }}">
                                        {{ \Carbon\Carbon::parse($inventory['fechacaducidad'])->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('inventory.edit', $inventory['id']) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inventory.destroy', $inventory['id']) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-2x mb-3"></i>
                                        <p class="mb-0">No hay productos en el inventario.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection