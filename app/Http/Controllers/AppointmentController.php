<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Muestra la lista de citas
    public function index()
    {
        // Datos de ejemplo; reemplaza con una consulta al modelo Appointment cuando esté disponible
        $appointments = [
            [
                'id'      => 1,
                'mascot'  => 'Firulais',
                'service' => 'Consulta General',
                'date'    => '2025-05-15',
                'time'    => '10:00',
            ],
            [
                'id'      => 2,
                'mascot'  => 'Luna',
                'service' => 'Vacunación',
                'date'    => '2025-05-20',
                'time'    => '14:30',
            ],
        ];

        return view('appointments.index', compact('appointments'));
    }

    // Muestra el formulario para crear una nueva cita
    public function create()
    {
        return view('appointments.create');
    }

    // Almacena la nueva cita
    public function store(Request $request)
    {
        // Validación de ejemplo (ajusta reglas según necesidades)
        $request->validate([
            'mascot'  => 'required|string|max:255',
            'service' => 'required|string',
            'date'    => 'required|date',
            'time'    => 'required',
        ]);

        // Aquí guardarías la cita en la base de datos.
        return redirect()->route('appointments.index')->with('status', 'Cita creada exitosamente.');
    }

    // Muestra el formulario para editar una cita existente
    public function edit($id)
    {
        // Datos de ejemplo para una cita. Reemplaza con consulta al modelo Appointment.
        $appointment = [
            'id'      => $id,
            'mascot'  => 'Firulais',
            'service' => 'Consulta General',
            'date'    => '2025-05-15',
            'time'    => '10:00',
        ];

        return view('appointments.edit', compact('appointment'));
    }

    // Actualiza la cita existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'mascot'  => 'required|string|max:255',
            'service' => 'required|string',
            'date'    => 'required|date',
            'time'    => 'required',
        ]);

        // Aquí actualizarías la cita en la base de datos.
        return redirect()->route('appointments.index')->with('status', 'Cita actualizada exitosamente.');
    }

    // Elimina una cita
    public function destroy($id)
    {
        // Aquí eliminarías la cita usando el modelo Appointment.
        return redirect()->route('appointments.index')->with('status', 'Cita eliminada exitosamente.');
    }
}