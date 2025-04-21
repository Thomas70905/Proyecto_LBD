@extends('layouts.app')

@section('title', 'Administración de Servicios')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Administración de Servicios</h2>
        <a href="{{ route('services.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Agregar Servicio
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
                            <th style="width: 30%">Descripción</th>
                            <th class="text-center" style="width: 15%">Duración</th>
                            <th class="text-end" style="width: 15%">Costo</th>
                            <th class="text-center" style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr>
                                <td class="text-center">{{ $service['id'] }}</td>
                                <td>
                                    <strong>{{ $service['nombre'] }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $service['descripcion'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <i class="fas fa-clock me-1"></i>{{ $service['duracion'] }} min
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-primary">${{ number_format($service['precio'], 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('services.edit', $service['id']) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('services.destroy', $service['id']) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Está seguro de eliminar este servicio?')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-concierge-bell fa-2x mb-3"></i>
                                        <p class="mb-0">No hay servicios disponibles.</p>
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