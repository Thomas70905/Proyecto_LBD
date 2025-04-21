<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Handlers\AppointmentHandler;
use App\Handlers\PetsHandler;
use App\Handlers\ServiceHandler;
use App\Handlers\UserHandler;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentNotificationMail;

class AppointmentController extends Controller
{
    protected $appointmentHandler;
    protected $petsHandler;
    protected $serviceHandler;
    protected $userHandler;

    public function __construct(
        AppointmentHandler $appointmentHandler,
        PetsHandler        $petsHandler,
        ServiceHandler    $serviceHandler,
        UserHandler       $userHandler
    ) {
        $this->appointmentHandler = $appointmentHandler;
        $this->petsHandler        = $petsHandler;
        $this->serviceHandler    = $serviceHandler;
        $this->userHandler       = $userHandler;
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
        $userId = auth()->id();
        $pets = $this->petsHandler->getPetsByUserId($userId);
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

        // Get client's email and send notification
        $userId = auth()->id();
        $user = $this->userHandler->getUsuarioPorId($userId);
        $pet = $this->petsHandler->getPetById($request->idMascota);
        $service = $this->serviceHandler->getServiceById($request->idServicio);

        if ($user) {
            Mail::to($user['email'])->send(new AppointmentNotificationMail([
                'nombre_cliente' => $user['nombre_completo'],
                'nombre_mascota' => $pet['nombre_completo'],
                'nombre_servicio' => $service['nombre'],
                'fecha' => $fechaHora->format('Y-m-d'),
                'hora' => $fechaHora->format('H:i'),
                'descripcion' => $request->descripcion
            ]));
        }

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