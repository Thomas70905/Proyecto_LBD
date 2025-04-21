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

/**
 * Controlador AppointmentController
 * 
 * Este controlador maneja todas las operaciones relacionadas con las citas en el sistema.
 * Proporciona métodos para crear, ver, eliminar citas y enviar notificaciones por correo
 * electrónico a los clientes cuando se crea una nueva cita.
 * 
 * El controlador utiliza varios handlers para interactuar con la base de datos y
 * gestionar la información relacionada con las citas, mascotas, servicios y usuarios.
 * 
 * @package App\Http\Controllers
 */
class AppointmentController extends Controller
{
    /**
     * @var AppointmentHandler Instancia del manejador de citas
     */
    protected $appointmentHandler;

    /**
     * @var PetsHandler Instancia del manejador de mascotas
     */
    protected $petsHandler;

    /**
     * @var ServiceHandler Instancia del manejador de servicios
     */
    protected $serviceHandler;

    /**
     * @var UserHandler Instancia del manejador de usuarios
     */
    protected $userHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param AppointmentHandler $appointmentHandler Manejador de citas
     * @param PetsHandler $petsHandler Manejador de mascotas
     * @param ServiceHandler $serviceHandler Manejador de servicios
     * @param UserHandler $userHandler Manejador de usuarios
     */
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

    /**
     * Muestra la lista de todas las citas
     * 
     * Este método obtiene todas las citas del sistema y las muestra en la vista
     * correspondiente.
     * 
     * @return \Illuminate\View\View Vista con la lista de citas
     */
    public function index()
    {
        $appointments = $this->appointmentHandler->getAllAppointments();
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Muestra el formulario para crear una nueva cita
     * 
     * Este método obtiene la lista de mascotas del usuario autenticado y todos los
     * servicios disponibles para mostrarlos en el formulario de creación de citas.
     * 
     * @return \Illuminate\View\View Vista con el formulario de creación de citas
     */
    public function create()
    {
        $userId = auth()->id();
        $pets = $this->petsHandler->getPetsByUserId($userId);
        $services = $this->serviceHandler->getAllServices();
        return view('appointments.create', compact('pets', 'services'));
    }

    /**
     * Almacena una nueva cita en el sistema
     * 
     * Este método valida y almacena una nueva cita en el sistema. Además, envía una
     * notificación por correo electrónico al cliente con los detalles de la cita.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - idMascota: ID de la mascota
     *                                         - idServicio: ID del servicio
     *                                         - fecha: Fecha de la cita
     *                                         - hora: Hora de la cita
     *                                         - descripcion: Descripción de la cita
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de citas con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
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
        $service = $this->serviceHandler->getServiceDetails($request->idServicio);

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

    /**
     * Elimina una cita del sistema
     * 
     * Este método elimina una cita específica del sistema y redirige al usuario
     * a la lista de citas con un mensaje de confirmación.
     * 
     * @param int $id ID de la cita a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de citas con mensaje de éxito
     */
    public function destroy($id)
    {
        $this->appointmentHandler->deleteAppointment($id);
        return redirect()->route('appointments.index')
                         ->with('status', 'Cita eliminada exitosamente.');
    }
}