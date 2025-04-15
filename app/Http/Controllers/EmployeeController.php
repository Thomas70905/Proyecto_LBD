<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\UserHandler;
use App\Handlers\VeterinariosHandler;
use Exception;

class EmployeeController extends Controller
{
    protected $userHandler;
    protected $veterinariosHandler;

    public function __construct(UserHandler $userHandler, VeterinariosHandler $veterinariosHandler)
    {
        $this->userHandler = $userHandler;
        $this->veterinariosHandler = $veterinariosHandler;
    }

    // Muestra la vista principal de administración de empleados
    public function index()
    {
        try {
            $veterinarios = $this->veterinariosHandler->getAllVeterinarios();
            return view('employees.index', compact('veterinarios'));
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los empleados.');
        }
    }

    // Muestra el formulario para crear un nuevo empleado
    public function create()
    {
        return view('employees.create');
    }

    // Almacena el nuevo empleado
    public function store(Request $request)
    {
        $request->validate([
            'nombreCompleto' => 'required|string|max:255',
            'fechaInicio'    => 'required|date',
            'telefono'       => 'required|string|max:50',
            'especialidad'   => 'required|string|max:255',
            'email'          => 'required|email|unique:usuarios,email',
        ]);

        // Set the password to the email
        $data = $request->only(['nombreCompleto', 'fechaInicio', 'telefono', 'especialidad', 'email']);
        $data['password'] = $data['email'];

        try {
            // Register the user and veterinarian
            $result = $this->userHandler->registerVeterinario($data);

            return redirect()->route('employees.index')->with('success', 'Empleado registrado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al registrar el empleado. Por favor, inténtalo de nuevo.');
        }
    }

    // Muestra el formulario para editar un empleado
    public function edit($id)
    {
        try {
            $veterinario = $this->veterinariosHandler->getVeterinarioById($id);

            if (!$veterinario) {
                return redirect()->route('employees.index')->with('error', 'Empleado no encontrado.');
            }

            return view('employees.edit', compact('veterinario'));
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los datos del empleado.');
        }
    }

    // Actualiza el empleado
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombreCompleto' => 'required|string|max:255',
            'fechaInicio'    => 'required|date',
            'telefono'       => 'required|string|max:50',
            'especialidad'   => 'required|string|max:255',
            'email'          => 'required|email|unique:usuarios,email,' . $id . ',id',
        ]);

        // Set the password to the email
        $data = $request->only(['nombreCompleto', 'fechaInicio', 'telefono', 'especialidad', 'email']);
        $data['password'] = $data['email'];

        try {
            $veterinario = $this->veterinariosHandler->getVeterinarioById($id);

            if (!$veterinario) {
                return redirect()->route('employees.index')->with('error', 'Empleado no encontrado.');
            }

            // Update the user in the "usuarios" table
            $this->userHandler->updateUsuario($veterinario['idusuario'], $data['email'], $data['password']);
            $data['idUsuario'] = $veterinario['idusuario'];

            // Update the veterinarian in the "veterinarios" table
            $this->veterinariosHandler->updateVeterinario($id, $data);

            return redirect()->route('employees.index')->with('success', 'Empleado actualizado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al actualizar el empleado. Por favor, inténtalo de nuevo.');
        }
    }

    // Elimina el empleado
    public function destroy($id)
    {
        try {
            $veterinario = $this->veterinariosHandler->getVeterinarioById($id);

            if (!$veterinario) {
                return redirect()->route('employees.index')->with('error', 'Empleado no encontrado.');
            }

            // Delete the veterinarian from the "veterinarios" table
            $this->veterinariosHandler->deleteVeterinario($id);

            // Delete the user from the "usuarios" table
            $this->userHandler->deleteUsuario($veterinario['idusuario']);

            return redirect()->route('employees.index')->with('success', 'Empleado eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al eliminar el empleado. Por favor, inténtalo de nuevo.');
        }
    }
}