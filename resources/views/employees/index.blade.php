@extends('layouts.app')

@section('title', 'Administración de Empleados')

@section('content')
<div class="container mt-5">
    <h2>Administración de Empleados</h2>
    <a href="{{ route('employees.create') }}" class="btn btn-success my-2">Agregar Empleado</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($veterinarios as $veterinario)
                    <tr>
                        <td>{{ $veterinario['id'] }}</td>
                        <td>{{ $veterinario['nombrecompleto'] }}</td>
                        <td>{{ $veterinario['especialidad'] }}</td>
                        <td>{{ $veterinario['telefono'] }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $veterinario['id']) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('employees.destroy', $veterinario['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection