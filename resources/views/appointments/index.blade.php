@extends('layouts.app')

@section('title', 'Administración de Citas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Administración de Citas</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-success">
            <i class="fas fa-calendar-plus me-2"></i>Agregar Cita
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">ID</th>
                            <th style="width: 15%">Mascota</th>
                            <th style="width: 15%">Servicio</th>
                            <th class="text-center" style="width: 10%">Fecha</th>
                            <th class="text-center" style="width: 10%">Hora</th>
                            <th style="width: 20%">Descripción</th>
                            <th class="text-center" style="width: 10%">Estado</th>
                            <th class="text-center" style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="text-center">{{ $appointment['id'] }}</td>
                                <td>
                                    <strong>{{ $appointment['mascota'] }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $appointment['servicio'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('Y-m-d') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $appointment['descripcion'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $appointment['asistencia'] == -1 ? 'warning' : ($appointment['asistencia'] == 1 ? 'success' : 'danger') }}">
                                        @switch($appointment['asistencia'])
                                            @case(-1) Pendiente @break
                                            @case(0) No asistió @break
                                            @case(1) Asistió @break
                                            @default Desconocido
                                        @endswitch
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('appointments.destroy', $appointment['id']) }}"
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Está seguro de eliminar esta cita?')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                        <p class="mb-0">No hay citas registradas.</p>
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