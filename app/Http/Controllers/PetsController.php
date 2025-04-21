<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\PetsHandler;
use App\Handlers\UserHandler;

/**
 * Controlador PetsController
 * 
 * Este controlador maneja todas las operaciones relacionadas con las mascotas
 * en el sistema. Proporciona funcionalidades para gestionar las mascotas de los
 * clientes, incluyendo su creación, visualización, actualización y eliminación.
 * 
 * El controlador utiliza el PetsHandler para interactuar con la base de datos
 * y el UserHandler para obtener información relacionada con los clientes.
 * 
 * @package App\Http\Controllers
 */
class PetsController extends Controller
{
    /**
     * @var PetsHandler Instancia del manejador de mascotas
     */
    protected $petsHandler;

    /**
     * @var UserHandler Instancia del manejador de usuarios
     */
    protected $userHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param PetsHandler $petsHandler Manejador de mascotas
     * @param UserHandler $userHandler Manejador de usuarios
     */
    public function __construct(PetsHandler $petsHandler, UserHandler $userHandler)
    {
        $this->petsHandler = $petsHandler;
        $this->userHandler = $userHandler;
    }

    /**
     * Muestra la lista de mascotas del usuario actual
     * 
     * Este método obtiene y muestra la lista de mascotas asociadas al cliente
     * que ha iniciado sesión en el sistema.
     * 
     * @return \Illuminate\View\View Vista con la lista de mascotas
     */
    public function index()
    {
        $userId = auth()->id();
        $pets = $this->petsHandler->getPetsByUserId($userId);
        return view('pets.index', compact('pets'));
    }

    /**
     * Muestra el formulario para crear una nueva mascota
     * 
     * Este método muestra la vista que contiene el formulario para registrar
     * una nueva mascota en el sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de registro
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Almacena una nueva mascota en el sistema
     * 
     * Este método valida y almacena una nueva mascota asociada al cliente
     * que ha iniciado sesión.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - nombre_completo: Nombre de la mascota
     *                                         - edad: Edad en años
     *                                         - peso: Peso en kilogramos
     *                                         - raza: Raza de la mascota
     *                                         - especie: Especie de la mascota
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'edad'            => 'required|integer',
            'peso'            => 'required|numeric',
            'raza'            => 'required|string|max:255',
            'especie'         => 'required|string|max:255',
        ]);

        $userId   = auth()->id();
        $clientId = $this->userHandler->getClienteIdPorUsuarioId($userId);

        $this->petsHandler->insertPet(
            $request->nombre_completo,
            $request->edad,
            $request->peso,
            $request->raza,
            $request->especie,
            $clientId
        );

        return redirect()->route('pets.index')
                         ->with('status', 'Mascota agregada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una mascota existente
     * 
     * Este método obtiene los datos de una mascota específica y muestra
     * el formulario para su edición.
     * 
     * @param int $id ID de la mascota a editar
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Vista con el formulario de edición o redirección si no se encuentra
     */
    public function edit($id)
    {
        $pet = $this->petsHandler->getPetById($id);

        if (! $pet) {
            return redirect()->route('pets.index')
                             ->with('error', 'Mascota no encontrada.');
        }

        return view('pets.edit', compact('pet'));
    }

    /**
     * Actualiza una mascota existente en el sistema
     * 
     * Este método valida y actualiza la información de una mascota específica
     * asociada al cliente que ha iniciado sesión.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - nombre_completo: Nuevo nombre
     *                                         - edad: Nueva edad
     *                                         - peso: Nuevo peso
     *                                         - raza: Nueva raza
     *                                         - especie: Nueva especie
     * @param int $id ID de la mascota a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'edad'            => 'required|integer',
            'peso'            => 'required|numeric',
            'raza'            => 'required|string|max:255',
            'especie'         => 'required|string|max:255',
        ]);

        $userId   = auth()->id();
        $clientId = $this->userHandler->getClienteIdPorUsuarioId($userId);

        $this->petsHandler->updatePet(
            $id,
            $request->nombre_completo,
            $request->edad,
            $request->peso,
            $request->raza,
            $request->especie,
            $clientId
        );

        return redirect()->route('pets.index')
                         ->with('status', 'Mascota actualizada exitosamente.');
    }

    /**
     * Elimina una mascota del sistema
     * 
     * Este método elimina una mascota específica del sistema.
     * 
     * @param int $id ID de la mascota a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     */
    public function destroy($id)
    {
        $this->petsHandler->deletePet($id);

        return redirect()->route('pets.index')
                         ->with('status', 'Mascota eliminada exitosamente.');
    }
}