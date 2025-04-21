<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\PetsHandler;
use App\Handlers\UserHandler;

class PetsController extends Controller
{
    protected $petsHandler;
    protected $userHandler;

    public function __construct(PetsHandler $petsHandler, UserHandler $userHandler)
    {
        $this->petsHandler = $petsHandler;
        $this->userHandler = $userHandler;
    }

    // Muestra la lista de mascotas
    public function index()
    {
        $pets = $this->petsHandler->getAllPets();
        return view('pets.index', compact('pets'));
    }

    // Muestra el formulario para crear una nueva mascota
    public function create()
    {
        return view('pets.create');
    }

    // Almacena una nueva mascota
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'edad'            => 'required|integer',
            'peso'            => 'required|numeric',
            'raza'            => 'required|string|max:255',
            'especie'         => 'required|string|max:255',
        ]);

        // obtener idUsuario actual y su idCliente
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

    // Muestra el formulario para editar una mascota existente
    public function edit($id)
    {
        $pet = $this->petsHandler->getPetById($id);

        if (! $pet) {
            return redirect()->route('pets.index')
                             ->with('error', 'Mascota no encontrada.');
        }

        return view('pets.edit', compact('pet'));
    }

    // Actualiza una mascota existente
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

    // Elimina una mascota
    public function destroy($id)
    {
        $this->petsHandler->deletePet($id);

        return redirect()->route('pets.index')
                         ->with('status', 'Mascota eliminada exitosamente.');
    }
}