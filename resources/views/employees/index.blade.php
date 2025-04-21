@extends('layouts.app')

@section('title', 'Administración de Empleados')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Administración de Empleados</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Agregar Empleado
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 25%">Nombre</th>
                            <th style="width: 20%">Teléfono</th>
                            <th style="width: 25%">Correo</th>
                            <th class="text-center" style="width: 15%">Rol</th>
                            <th class="text-center" style="width: 10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($empleados as $empleado)
                            <tr>
                                <td class="text-center">{{ $empleado['id'] }}</td>
                                <td>
                                    <strong>{{ $empleado['nombre_completo'] }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-phone me-2"></i>{{ $empleado['telefono'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="fas fa-envelope me-2"></i>{{ $empleado['email'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $empleado['rol'] === 'administrador' ? 'danger' : ($empleado['rol'] === 'veterinario' ? 'info' : 'success') }}">
                                        {{ ucfirst($empleado['rol']) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('employees.destroy', $empleado['id']) }}"
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Está seguro de eliminar este empleado?')"
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
                                        <i class="fas fa-users fa-2x mb-3"></i>
                                        <p class="mb-0">No hay empleados registrados.</p>
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