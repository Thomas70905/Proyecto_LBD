@extends('layouts.app')

@section('title', 'Administración de Citas')

@section('content')
<div class="container mt-5">
    <h2>Administración de Citas</h2>
    <a href="{{ route('appointments.create') }}" class="btn btn-success my-2">Agregar Cita</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mascota</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                    <th>Asistencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment['id'] }}</td>
                        <td>{{ $appointment['mascota'] }}</td>
                        <td>{{ $appointment['servicio'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment['fechainicio'])->format('H:i') }}</td>
                        <td>{{ $appointment['descripcion'] }}</td>
                        <td>
                            @switch($appointment['asistencia'])
                                @case(-1) Pendiente @break
                                @case(0) No asistió @break
                                @case(1) Asistió @break
                                @default Desconocido
                            @endswitch
                        </td>
                        <td>
                            <form action="{{ route('appointments.destroy', $appointment['id']) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar esta cita?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay citas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection