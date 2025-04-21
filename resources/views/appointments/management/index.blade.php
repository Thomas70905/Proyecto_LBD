@extends('layouts.app')

@section('title', 'Gestión de Citas')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Lista de Citas</h3>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%">Fecha y Hora</th>
                                    <th style="width: 20%">Mascota</th>
                                    <th style="width: 20%">Cliente</th>
                                    <th style="width: 15%">Servicio</th>
                                    <th class="text-center" style="width: 15%">Estado</th>
                                    <th class="text-center" style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                {{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment['nombre_mascota'] }}</strong>
                                            <small class="d-block text-muted">
                                                <i class="fas fa-paw me-1"></i>{{ $appointment['especie_mascota'] }} - {{ $appointment['raza_mascota'] }}
                                            </small>
                                        </td>
                                        <td>
                                            <strong>{{ $appointment['nombre_cliente'] }}</strong>
                                            <small class="d-block text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $appointment['telefono_cliente'] }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $appointment['nombre_servicio'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $appointment['asistencia'] == -1 ? 'warning' : ($appointment['asistencia'] == 1 ? 'success' : 'danger') }}">
                                                @switch($appointment['asistencia'])
                                                    @case(-1) Pendiente @break
                                                    @case(0) No Asistió @break
                                                    @case(1) Asistió @break
                                                    @default Desconocido
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('appointment-management.show', $appointment['id']) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                                <p class="mb-0">No hay citas registradas</p>
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
    </div>
</div>
@endsection 