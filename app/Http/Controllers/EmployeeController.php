<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\UserHandler;
use Exception;
use Illuminate\Support\Arr;

class EmployeeController extends Controller
{
    protected UserHandler $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    // Lista todos los empleados (veterinarios y administradores)
    public function index()
    {
        try {
            $empleados = $this->userHandler->getEmpleados();
            return view('employees.index', ['veterinarios' => $empleados]);
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los empleados.');
        }
    }

    // Muestra el formulario para crear un nuevo empleado
    public function create()
    {
        return view('employees.create');
    }

    // Almacena el nuevo empleado (rol veterinario)
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono'       => 'required|string|max:50',
            'email'          => 'required|email|unique:usuarios,email',
        ]);

        $data = $request->only(['nombre_completo', 'telefono', 'email']);
        $data['password'] = $data['email'];
        try {
            $this->userHandler->registerVeterinario([
                'email'            => $data['email'],
                'password'         => $data['password'],
                'nombre_completo'  => $data['nombre_completo'],
                'telefono'         => $data['telefono'],
            ]);
            return redirect()->route('employees.index')
                             ->with('success', 'Empleado registrado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al registrar el empleado.');
        }
    }

    // Muestra el formulario para editar un empleado
    public function edit(int $id)
    {
        try {
            $empleados  = $this->userHandler->getEmpleados();
            $veterinario = Arr::first($empleados, fn($e) => $e['id'] === $id);

            if (!$veterinario) {
                return redirect()->route('employees.index')
                                 ->with('error', 'Empleado no encontrado.');
            }
            return view('employees.edit', compact('veterinario'));
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los datos del empleado.');
        }
    }

    // Actualiza el empleado (solo datos en usuarios)
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono'       => 'required|string|max:50',
            'email'          => 'required|email|unique:usuarios,email,' . $id . ',id',
        ]);

        $v = $request->only(['nombre_completo', 'telefono', 'email']);
        $v['password'] = $v['email'];

        try {
            $this->userHandler->updateUsuario(
                $id,
                $v['email'],
                $v['password'],
                'veterinario',
                $v['nombre_completo'],
                $v['telefono'],
                0
            );
            return redirect()->route('employees.index')
                             ->with('success', 'Empleado actualizado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al actualizar el empleado.');
        }
    }

    // Elimina el empleado (rol veterinario)
    public function destroy(int $id)
    {
        try {
            $this->userHandler->deleteUsuario($id);
            return redirect()->route('employees.index')
                             ->with('success', 'Empleado eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al eliminar el empleado.');
        }
    }
}