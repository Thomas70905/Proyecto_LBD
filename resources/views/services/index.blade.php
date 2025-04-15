@extends('layouts.app')

@section('title', 'Administración de Servicios')

@section('content')
<div class="container mt-5">
    <h2>Administración de Servicios</h2>
    <a href="{{ route('services.create') }}" class="btn btn-success my-2">Agregar Servicio</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Duración (min)</th>
                    <th>Costo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td>{{ $service['id'] }}</td>
                        <td>{{ $service['nombre'] }}</td>
                        <td>{{ $service['descripcion'] }}</td>
                        <td>{{ $service['duracion'] }}</td>
                        <td>${{ number_format($service['precio'], 2) }}</td>
                        <td>
                            <a href="{{ route('services.edit', $service['id']) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('services.destroy', $service['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este servicio?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay servicios disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection