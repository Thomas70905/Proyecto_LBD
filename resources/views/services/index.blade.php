@extends('layouts.app')

@section('title', 'Administración de Servicios')

@section('content')
<div class="container mt-5">
    <h2>Administración de Servicios</h2>
    <a href="/services/create" class="btn btn-success my-2">Agregar Servicio</a>
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
                <!-- Hardcoded sample service -->
                <tr>
                    <td>1</td>
                    <td>Servicio A</td>
                    <td>Descripción del Servicio A</td>
                    <td>60</td>
                    <td>$50.00</td>
                    <td>
                        <a href="/services/1/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/services/1" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Está seguro de eliminar este servicio?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection