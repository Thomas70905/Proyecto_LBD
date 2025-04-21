<?php

namespace App\Http\Controllers;

use App\Handlers\ServiceHandler;
use Illuminate\Http\Request;

/**
 * Controlador ServiceController
 * 
 * Este controlador maneja todas las operaciones relacionadas con los servicios
 * en el sistema. Proporciona funcionalidades para gestionar los servicios ofrecidos
 * por la veterinaria, incluyendo su creación, visualización, actualización y eliminación.
 * 
 * El controlador utiliza el ServiceHandler para interactuar con la base de datos
 * a través de procedimientos almacenados, asegurando la integridad y consistencia
 * de los datos.
 * 
 * @package App\Http\Controllers
 */
class ServiceController extends Controller
{
    /**
     * @var ServiceHandler Instancia del manejador de servicios
     */
    protected $serviceHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param ServiceHandler $serviceHandler Manejador de servicios
     */
    public function __construct(ServiceHandler $serviceHandler)
    {
        $this->serviceHandler = $serviceHandler;
    }

    /**
     * Muestra la lista de todos los servicios
     * 
     * Este método obtiene y muestra la lista completa de servicios disponibles
     * en el sistema.
     * 
     * @return \Illuminate\View\View Vista con la lista de servicios
     */
    public function index()
    {
        // Fetch services using the handler
        $services = $this->serviceHandler->getAllServices();

        return view('services.index', compact('services'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio
     * 
     * Este método muestra la vista que contiene el formulario para registrar
     * un nuevo servicio en el sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de registro
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Almacena un nuevo servicio en el sistema
     * 
     * Este método valida y almacena un nuevo servicio en el sistema.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - name: Nombre del servicio
     *                                         - description: Descripción detallada
     *                                         - duration: Duración en minutos
     *                                         - cost: Costo del servicio
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|integer',
            'cost'        => 'required|numeric',
        ]);

        // Llama al procedimiento almacenado para insertar el servicio
        $this->serviceHandler->createService(
            $request->name,
            $request->description,
            $request->cost,
            $request->duration
        );

        return redirect()->route('services.index')->with('status', 'Servicio agregado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un servicio existente
     * 
     * Este método obtiene los datos de un servicio específico y muestra
     * el formulario para su edición.
     * 
     * @param int $id ID del servicio a editar
     * @return \Illuminate\View\View Vista con el formulario de edición
     */
    public function edit($id)
    {
        // Fetch the service by ID using the handler
        $service = $this->serviceHandler->getServiceDetails($id);

        return view('services.edit', compact('service'));
    }

    /**
     * Actualiza un servicio existente en el sistema
     * 
     * Este método valida y actualiza la información de un servicio específico
     * en el sistema.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - name: Nuevo nombre
     *                                         - description: Nueva descripción
     *                                         - duration: Nueva duración
     *                                         - cost: Nuevo costo
     * @param int $id ID del servicio a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|integer',
            'cost'        => 'required|numeric',
        ]);

        // Llama al procedimiento almacenado para actualizar el servicio
        $this->serviceHandler->updateService(
            $id,
            $request->name,
            $request->description,
            $request->cost,
            $request->duration
        );

        return redirect()->route('services.index')->with('status', 'Servicio actualizado exitosamente.');
    }

    /**
     * Elimina un servicio del sistema
     * 
     * Este método elimina un servicio específico del sistema.
     * 
     * @param int $id ID del servicio a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     */
    public function destroy($id)
    {
        // Llama al procedimiento almacenado para eliminar el servicio
        $this->serviceHandler->deleteService($id);

        return redirect()->route('services.index')->with('status', 'Servicio eliminado exitosamente.');
    }
}