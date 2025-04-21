{{--
    Vista: Historial de Citas
    Descripción: Muestra el listado de citas agrupadas por mascota para el usuario autenticado.
    
    Estructura:
    1. Layout principal
    2. Contenedor de contenido
    3. Tarjetas por mascota
    4. Tabla de citas
    5. Mensajes de estado
    
    Componentes:
    - Alertas de sesión
    - Tarjetas de mascotas
    - Tablas de citas
    - Badges de estado
    - Botones de acción
    
    Datos requeridos:
    - appointments: Array de citas agrupadas por mascota
    - Cada cita debe contener:
      * id
      * fechainicio
      * nombre_servicio
      * asistencia
      * total
      * nombre_mascota
      * especie
      * raza
    
    Estilos:
    - Bootstrap 5
    - Personalización de colores
    - Diseño responsive
    
    @extends('layouts.app')
    @section('title', 'Historial de Citas')
--}}

@extends('layouts.app')

@section('title', 'Historial de Citas')

@section('content')
<div class="container py-4">
    {{-- Título de la página --}}
    <h1 class="mb-4">Historial de Citas</h1>

    {{-- Mensajes de sesión --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Contenedor de citas agrupadas por mascota --}}
    @forelse($appointments as $petName => $petAppointments)
        <div class="card mb-4">
            {{-- Encabezado de la tarjeta con información de la mascota --}}
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    {{ $petName }}
                    <small class="d-block">
                        {{ $petAppointments[0]['especie'] }} - {{ $petAppointments[0]['raza'] }}
                    </small>
                </h5>
            </div>

            {{-- Tabla de citas de la mascota --}}
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($petAppointments as $appointment)
                            <tr>
                                {{-- Fecha de la cita --}}
                                <td>{{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('d/m/Y H:i') }}</td>
                                
                                {{-- Nombre del servicio --}}
                                <td>{{ $appointment['nombre_servicio'] }}</td>
                                
                                {{-- Estado de asistencia con badge de color --}}
                                <td>
                                    @switch($appointment['asistencia'])
                                        @case(0)
                                            <span class="badge bg-warning">Pendiente</span>
                                            @break
                                        @case(1)
                                            <span class="badge bg-success">Asistió</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-danger">No Asistió</span>
                                            @break
                                    @endswitch
                                </td>
                                
                                {{-- Total de la cita --}}
                                <td>₡{{ number_format($appointment['total'], 2) }}</td>
                                
                                {{-- Botones de acción --}}
                                <td>
                                    {{-- Botón para ver detalles --}}
                                    <a href="{{ route('appointment-history.show', $appointment['id']) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    
                                    {{-- Botón para descargar PDF (solo si asistió) --}}
                                    @if($appointment['asistencia'] == 1)
                                        <a href="{{ route('appointment-history.download-pdf', $appointment['id']) }}" 
                                           class="btn btn-sm btn-secondary">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        {{-- Mensaje cuando no hay citas --}}
        <div class="alert alert-info">
            No hay citas registradas para tus mascotas.
        </div>
    @endforelse
</div>
@endsection 