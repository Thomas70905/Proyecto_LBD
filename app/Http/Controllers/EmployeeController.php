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

/**
 * Controlador EmployeeController
 * 
 * Este controlador maneja todas las operaciones relacionadas con los empleados en el sistema.
 * Proporciona funcionalidades para gestionar veterinarios y administradores, incluyendo
 * su registro, visualización y eliminación. También maneja el proceso de recuperación
 * de contraseña para nuevos empleados.
 * 
 * El controlador utiliza el UserHandler para interactuar con la base de datos y
 * gestionar la información de los empleados.
 * 
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
    /**
     * @var UserHandler Instancia del manejador de usuarios
     */
    protected UserHandler $userHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa las dependencias necesarias para el funcionamiento del controlador.
     * 
     * @param UserHandler $userHandler Manejador de usuarios
     */
    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**
     * Muestra la lista de todos los empleados
     * 
     * Este método obtiene y muestra la lista de todos los empleados del sistema
     * (veterinarios y administradores) en la vista correspondiente.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Vista con la lista de empleados o redirección con mensaje de error
     * 
     * @throws \Exception Si ocurre un error al cargar los empleados
     */
    public function index()
    {
        try {
            $empleados = $this->userHandler->getEmpleados();
            return view('employees.index', ['empleados' => $empleados]);
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al cargar los empleados.');
        }
    }

    /**
     * Muestra el formulario para crear un nuevo empleado
     * 
     * Este método muestra el formulario que permite registrar un nuevo empleado
     * en el sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de registro
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Almacena un nuevo empleado en el sistema
     * 
     * Este método valida y almacena un nuevo empleado en el sistema, enviando
     * un correo electrónico con instrucciones para establecer su contraseña.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - nombre_completo: Nombre completo del empleado
     *                                         - telefono: Número de teléfono
     *                                         - email: Correo electrónico
     *                                         - rol: Rol del empleado (veterinario/administrador)
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     * @throws \Exception Si ocurre un error al registrar el empleado
     */
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

    /**
     * Elimina un empleado del sistema
     * 
     * Este método elimina un empleado específico del sistema, incluyendo su registro
     * de usuario y su registro específico según su rol (veterinario o administrador).
     * 
     * @param int $id ID del empleado a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Exception Si ocurre un error al eliminar el empleado
     */
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