<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Handlers\AppointmentHandler;
use App\Handlers\PetsHandler;
use App\Handlers\ServiceHandler;

class AppointmentController extends Controller
{
    protected $appointmentHandler;
    protected $petsHandler;
    protected $serviceHandler;

    public function __construct(
        AppointmentHandler $appointmentHandler,
        PetsHandler        $petsHandler,
        ServiceHandler    $serviceHandler
    ) {
        $this->appointmentHandler = $appointmentHandler;
        $this->petsHandler        = $petsHandler;
        $this->serviceHandler    = $serviceHandler;
    }

    // Muestra la lista de citas
    public function index()
    {
        $appointments = $this->appointmentHandler->getAllAppointments();
        return view('appointments.index', compact('appointments'));
    }

    // Formulario para crear una nueva cita
    public function create()
    {
        $pets     = $this->petsHandler->getAllPets();
        $services = $this->serviceHandler->getAllServices();
        return view('appointments.create', compact('pets', 'services'));
    }

    // Almacena la nueva cita
    public function store(Request $request)
    {
        $request->validate([
            'idMascota'  => 'required|integer',
            'idServicio' => 'required|integer',
            'fecha'      => 'required|date',
            'hora'       => 'required',
            'descripcion'=> 'required|string',
        ]);

        $fechaHora = Carbon::parse($request->fecha . ' ' . $request->hora);
        // asistencia por defecto -1 (pendiente)
        $this->appointmentHandler->insertAppointment(
            $request->idMascota,
            $fechaHora,
            $request->idServicio,
            $request->descripcion,
            -1
        );

        return redirect()->route('appointments.index')
                         ->with('status', 'Cita creada exitosamente.');
    }

    // Elimina una cita
    public function destroy($id)
    {
        $this->appointmentHandler->deleteAppointment($id);
        return redirect()->route('appointments.index')
                         ->with('status', 'Cita eliminada exitosamente.');
    }
}