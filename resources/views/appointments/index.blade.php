@extends('layouts.app')

@section('title', 'Administración de Citas')

@section('content')
<div class="container mt-5">
    <h2>Administración de Citas</h2>
    <a href="/appointments/create" class="btn btn-success my-2">Agregar Cita</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mascota</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hardcoded sample appointments -->
                <tr>
                    <td>1</td>
                    <td>Firulais</td>
                    <td>Consulta General</td>
                    <td>2025-05-15</td>
                    <td>10:00</td>
                    <td>
                        <a href="/appointments/1/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/appointments/1" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cita?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Luna</td>
                    <td>Vacunación</td>
                    <td>2025-05-20</td>
                    <td>14:30</td>
                    <td>
                        <a href="/appointments/2/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/appointments/2" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta cita?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <!-- End hardcoded rows -->
            </tbody>
        </table>
    </div>
</div>
@endsection