<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Muestra la lista de servicios
    public function index()
    {
        // Datos de ejemplo; reemplaza con consultas al modelo Service cuando lo tengas
        $services = [
            [
                'id'          => 1,
                'name'        => 'Servicio A',
                'description' => 'Descripción del Servicio A',
                'duration'    => 60,
                'cost'        => 50.00,
            ],
            [
                'id'          => 2,
                'name'        => 'Servicio B',
                'description' => 'Descripción del Servicio B',
                'duration'    => 45,
                'cost'        => 35.00,
            ],
        ];

        return view('services.index', compact('services'));
    }

    // Muestra el formulario para crear un nuevo servicio
    public function create()
    {
        return view('services.create');
    }

    // Almacena el nuevo servicio
    public function store(Request $request)
    {
        // Validación de ejemplo (ajusta según necesites)
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|integer',
            'cost'        => 'required|numeric',
        ]);

        // Aquí guardarías el servicio en la base de datos.
        // Por ahora, redirige al índice con un mensaje de éxito.
        return redirect()->route('services.index')->with('status', 'Servicio agregado exitosamente.');
    }

    // Muestra el formulario para editar un servicio existente
    public function edit($id)
    {
        // Datos de ejemplo para un servicio a editar. Reemplaza con consulta al modelo Service.
        $service = [
            'id'          => $id,
            'name'        => 'Servicio A',
            'description' => 'Descripción del Servicio A',
            'duration'    => 60,
            'cost'        => 50.00,
        ];

        return view('services.edit', compact('service'));
    }

    // Actualiza el servicio existente
    public function update(Request $request, $id)
    {
        // Validación de ejemplo (ajusta según necesites)
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|integer',
            'cost'        => 'required|numeric',
        ]);

        // Aquí actualizarías el servicio en la base de datos.
        // Por ahora, redirige al índice con un mensaje de éxito.
        return redirect()->route('services.index')->with('status', 'Servicio actualizado exitosamente.');
    }

    // Elimina un servicio
    public function destroy($id)
    {
        // Aquí eliminarías el servicio usando el modelo Service.
        // Por ahora, redirige al índice con un mensaje de éxito.
        return redirect()->route('services.index')->with('status', 'Servicio eliminado exitosamente.');
    }
}