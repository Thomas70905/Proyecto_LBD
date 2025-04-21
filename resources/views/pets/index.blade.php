@extends('layouts.app')

@section('title', 'Administración de Mascotas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Administración de Mascotas</h2>
        <a href="{{ route('pets.create') }}" class="btn btn-success">
            <i class="fas fa-paw me-2"></i>Agregar Mascota
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
                            <th class="text-center" style="width: 10%">Edad</th>
                            <th class="text-center" style="width: 10%">Peso</th>
                            <th class="text-center" style="width: 15%">Raza</th>
                            <th class="text-center" style="width: 15%">Especie</th>
                            <th class="text-center" style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pets as $pet)
                            <tr>
                                <td class="text-center">{{ $pet['id'] }}</td>
                                <td>
                                    <strong>{{ $pet['nombre_completo'] }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $pet['edad'] }} años</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ number_format($pet['peso'], 2) }} kg</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $pet['raza'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $pet['especie'] === 'Perro' ? 'warning' : 'success' }}">
                                        {{ $pet['especie'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pets.edit', $pet['id']) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('pets.destroy', $pet['id']) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Seguro que deseas eliminar esta mascota?')"
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
                                        <i class="fas fa-paw fa-2x mb-3"></i>
                                        <p class="mb-0">No hay mascotas registradas.</p>
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