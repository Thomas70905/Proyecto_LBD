@extends('layouts.app')

@section('title', 'Administración de Inventario')

@section('content')
<div class="container mt-5">
    <h2>Administración de Inventario</h2>
    <a href="/inventory/create" class="btn btn-success my-2">Agregar Producto</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hardcoded sample products -->
                <tr>
                    <td>1</td>
                    <td>Producto A</td>
                    <td>Descripción del Producto A</td>
                    <td>$10.00</td>
                    <td>100</td>
                    <td>
                        <a href="/inventory/1/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/inventory/1" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Producto B</td>
                    <td>Descripción del Producto B</td>
                    <td>$20.00</td>
                    <td>50</td>
                    <td>
                        <a href="/inventory/2/edit" class="btn btn-sm btn-primary">Editar</a>
                        <form action="/inventory/2" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">
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