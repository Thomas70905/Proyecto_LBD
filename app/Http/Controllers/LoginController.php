<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador LoginController
 * 
 * Este controlador maneja todas las operaciones relacionadas con la autenticación
 * de usuarios en el sistema. Proporciona funcionalidades para mostrar el formulario
 * de inicio de sesión, procesar las credenciales de los usuarios, manejar la
 * recuperación de contraseña y gestionar el cierre de sesión.
 * 
 * El controlador utiliza el sistema de autenticación de Laravel para validar
 * las credenciales y gestionar las sesiones de los usuarios.
 * 
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión
     * 
     * Este método muestra la vista que contiene el formulario de inicio de sesión
     * para los usuarios del sistema.
     * 
     * @return \Illuminate\View\View Vista con el formulario de inicio de sesión
     */
    public function show()
    {
        return view('login');
    }

    /**
     * Procesa el inicio de sesión de un usuario
     * 
     * Este método valida y procesa las credenciales proporcionadas por el usuario.
     * Si las credenciales son correctas, inicia una nueva sesión y redirige al usuario
     * según su estado (normal o en recuperación de contraseña).
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - email: Correo electrónico del usuario
     *                                         - password: Contraseña del usuario
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *         Redirección a la página principal o vista de cambio de contraseña
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email'   => $request->input('email'),
            'password' => $request->input('password'),
        ];
    

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Si el usuario está en recuperación, redirige a una vista especial
            if ($user->en_recuperacion) {
                return view('change_password');
            }

            return redirect()->intended('/');
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }

    /**
     * Cierra la sesión del usuario actual
     * 
     * Este método finaliza la sesión del usuario actual, invalidando la sesión
     * y regenerando el token CSRF para mayor seguridad.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud
     * @return \Illuminate\Http\RedirectResponse Redirección a la página principal
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}