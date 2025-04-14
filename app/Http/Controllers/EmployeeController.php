<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Muestra la vista principal de administración de empleados
    public function index()
    {
        // Por el momento se usarán datos hardcodeados en la vista
        return view('employees.index');
    }

    // Muestra el formulario para crear un nuevo empleado
    public function create()
    {
        return view('employees.create');
    }

    // Almacena el nuevo empleado
    public function store(Request $request)
    {
        // Aquí agregarás la lógica para almacenar el empleado.
        // Por ahora, redirigimos de vuelta al índice.
        return redirect()->route('employees.index');
    }

    // Muestra el formulario para editar un empleado
    public function edit($id)
    {
        // Aquí deberías buscar el empleado por $id y pasarlo a la vista.
        return view('employees.edit', compact('id'));
    }

    // Actualiza el empleado
    public function update(Request $request, $id)
    {
        // Aquí agregarás la lógica para actualizar el empleado.
        return redirect()->route('employees.index');
    }

    // Elimina el empleado
    public function destroy($id)
    {
        // Lógica para eliminar el empleado.
        return redirect()->route('employees.index');
    }
}