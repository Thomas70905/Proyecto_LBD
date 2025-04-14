@extends('layouts.app')

@section('title', 'Agregar Empleado')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Agregar Empleado</h2>
            <form action="/employees" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Introduce el nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Introduce el correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="position" class="form-label">Puesto</label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="Introduce el puesto" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection