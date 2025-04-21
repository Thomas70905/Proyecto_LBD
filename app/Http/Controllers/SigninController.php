<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\UserHandler;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Controlador SignInController
 * 
 * Este controlador maneja el registro de nuevos clientes en el sistema.
 * Proporciona funcionalidades para mostrar el formulario de registro
 * y procesar la creación de usuarios mediante procedimientos almacenados
 * a través del UserHandler.
 * 
 * @package App\Http\Controllers
 */
class SignInController extends Controller
{
    /**
     * @var UserHandler Instancia del manejador de usuarios
     */
    protected $userHandler;

    /**
     * Constructor del controlador
     * 
     * Inicializa el UserHandler utilizado para la gestión de usuarios.
     * 
     * @param UserHandler $userHandler Manejador de usuarios
     */
    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    /**
     * Muestra el formulario de registro de clientes
     * 
     * Este método retorna la vista con el formulario para registrar
     * un nuevo cliente en el sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de registro
     */
    public function show()
    {
        return view('signin');
    }

    /**
     * Procesa el registro de un nuevo cliente
     * 
     * Este método valida los datos ingresados, llama al UserHandler
     * para ejecutar el procedimiento almacenado de registro y maneja
     * la redirección con el resultado de la operación.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                      - nombre_completo: Nombre completo del cliente
     *                                      - email: Correo electrónico único
     *                                      - password: Contraseña confirmada
     *                                      - telefono: Número de teléfono
     *                                      - direccion: Dirección de residencia
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     * @throws \Exception Si ocurre un error al registrar el usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre_completo'       => 'required|string|max:255',
            'email'      => 'required|email|unique:usuarios,email',
            'password'   => 'required|confirmed|min:6',
            'telefono'   => 'required|string|max:50',
            'direccion'  => 'required|string|max:255'
        ]);

        $data = $request->only(['nombre_completo', 'email', 'password', 'telefono', 'direccion']);

        try {
            $this->userHandler->registerClient($data);

            return redirect('/login')
                   ->with('success', 'Usuario registrado con éxito. Ahora inicia sesión.');
        } catch (Exception $e) {
            return back()->with('error', 'Hubo un problema al registrar el usuario. Por favor, inténtalo de nuevo.');
        }
    }
}