<?php

namespace App\Http\Controllers;

use App\Handlers\ServiceHandler;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceHandler;

    public function __construct(ServiceHandler $serviceHandler)
    {
        $this->serviceHandler = $serviceHandler;
    }

    // Muestra la lista de servicios
    public function index()
    {
        // Fetch services using the handler
        $services = $this->serviceHandler->getAllServices();

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
        // Validación de los datos
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'duration'    => 'required|integer',
            'cost'        => 'required|numeric',
        ]);

        // Llama al procedimiento almacenado para insertar el servicio
        $this->serviceHandler->insertService(
            $request->name,
            $request->description,
            $request->cost,
            $request->duration
        );

        return redirect()->route('services.index')->with('status', 'Servicio agregado exitosamente.');
    }

    // Muestra el formulario para editar un servicio existente
    public function edit($id)
    {
        // Fetch the service by ID using the handler
        $service = $this->serviceHandler->getServiceById($id);

        return view('services.edit', compact('service'));
    }

    // Actualiza el servicio existente
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

    // Elimina un servicio
    public function destroy($id)
    {
        // Llama al procedimiento almacenado para eliminar el servicio
        $this->serviceHandler->deleteService($id);

        return redirect()->route('services.index')->with('status', 'Servicio eliminado exitosamente.');
    }
}