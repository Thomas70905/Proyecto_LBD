<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\UserHandler;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\ResetPasswordMail;

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
            return view('employees.index', ['empleados' => $empleados]);
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los empleados.');
        }
    }

    // Formulario para crear un nuevo empleado
    public function create()
    {
        return view('employees.create');
    }

    // Guarda un empleado (según rol)
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono'        => 'required|string|max:50',
            'email'           => 'required|email|unique:usuarios,email',
            'rol'             => 'required|in:veterinario,administrador',
        ]);

        $data = $request->only(['nombre_completo', 'telefono', 'email', 'rol']);
        $data['password'] = $data['email'];

        try {
            if ($data['rol'] === 'veterinario') {
                $this->userHandler->registerVeterinario([
                    'email'           => $data['email'],
                    'password'        => $data['password'],
                    'nombre_completo' => $data['nombre_completo'],
                    'telefono'        => $data['telefono'],
                ]);
            } else {
                $this->userHandler->registerAdministrador([
                    'email'           => $data['email'],
                    'password'        => $data['password'],
                    'nombre_completo' => $data['nombre_completo'],
                    'telefono'        => $data['telefono'],
                ]);
            }
            $token       = \Illuminate\Support\Str::random(16);
            $hashedToken = Hash::make($token);
            $this->userHandler->marcarUsuarioRecuperacion($data['email'], $hashedToken);
            Mail::to($data['email'])->send(new ResetPasswordMail($token));

            return redirect()->route('employees.index')
                             ->with('success', 'Empleado registrado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al registrar el empleado.');
        }
    }

    // Elimina el empleado
    public function destroy(int $id)
    {
        try {
            $empleado = $this->userHandler->getUsuarioPorId($id);
    
            if (!$empleado) {
                return redirect()->route('employees.index')
                                 ->with('error', 'Empleado no encontrado.');
            }
            
            if (isset($empleado['rol'])) {
                if ($empleado['rol'] === 'veterinario') {
                    $this->userHandler->deleteVeterinarioPorUsuarioId($id);
                } elseif ($empleado['rol'] === 'administrador') {
                    $this->userHandler->deleteAdministradorPorUsuarioId($id);
                }
            } else {
                return redirect()->route('employees.index')
                                 ->with('error', 'No se pudo determinar el rol del empleado.');
            }
            
            $this->userHandler->deleteUsuario($id);
    
            return redirect()->route('employees.index')
                             ->with('success', 'Empleado eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al eliminar el empleado.');
        }
    }
}