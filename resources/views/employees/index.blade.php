@extends('layouts.app')

@section('title', 'Administración de Empleados')

@section('content')
<div class="container mt-5">
    <h2>Administración de Empleados</h2>
    <a href="/employees/create" class="btn btn-success my-2">Agregar Empleado</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Puesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hardcoded sample employees for demonstration -->
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juan.perez@example.com</td>
                    <td>Gerente</td>
                    <td>
                        <a href="/employees/1/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/employees/1" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Ana Gómez</td>
                    <td>ana.gomez@example.com</td>
                    <td>Asistente</td>
                    <td>
                        <a href="/employees/2/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/employees/2" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos López</td>
                    <td>carlos.lopez@example.com</td>
                    <td>Supervisor</td>
                    <td>
                        <a href="/employees/3/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/employees/3" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- End hardcoded rows -->
            </tbody>
        </table>
    </div>
</div>
@endsection