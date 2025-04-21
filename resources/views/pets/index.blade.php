@extends('layouts.app')

@section('title', 'Administración de Mascotas')

@section('content')
<div class="container mt-5">
    <h2>Administración de Mascotas</h2>
    <a href="{{ route('pets.create') }}" class="btn btn-success my-2">Agregar Mascota</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Edad</th>
                    <th>Peso (kg)</th>
                    <th>Raza</th>
                    <th>Especie</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pets as $pet)
                    <tr>
                        <td>{{ $pet['id'] }}</td>
                        <td>{{ $pet['nombre_completo'] }}</td>
                        <td>{{ $pet['edad'] }}</td>
                        <td>{{ number_format($pet['peso'], 2) }}</td>
                        <td>{{ $pet['raza'] }}</td>
                        <td>{{ $pet['especie'] }}</td>
                        <td>
                            <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Seguro que deseas eliminar esta mascota?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay mascotas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection