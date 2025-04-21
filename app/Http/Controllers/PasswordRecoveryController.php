<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\ResetPasswordMail;
use App\Handlers\UserHandler;

/**
 * Controlador PasswordRecoveryController
 * 
 * Este controlador maneja todas las operaciones relacionadas con la recuperación
 * y restablecimiento de contraseñas en el sistema. Proporciona funcionalidades
 * para solicitar la recuperación de contraseña, enviar enlaces de restablecimiento
 * por correo electrónico y actualizar las contraseñas de los usuarios.
 * 
 * El controlador utiliza el UserHandler para interactuar con la base de datos
 * y el sistema de correo electrónico de Laravel para enviar notificaciones.
 * 
 * @package App\Http\Controllers
 */
class PasswordRecoveryController extends Controller
{
    /**
     * Muestra el formulario de recuperación de contraseña
     * 
     * Este método muestra la vista que contiene el formulario para solicitar
     * la recuperación de contraseña mediante correo electrónico.
     * 
     * @return \Illuminate\View\View Vista con el formulario de recuperación
     */
    public function show()
    {
        return view('password_recovery');
    }

    /**
     * Procesa el envío del enlace de restablecimiento de contraseña
     * 
     * Este método valida el correo electrónico proporcionado, genera un token
     * de recuperación, marca al usuario como en recuperación y envía un correo
     * electrónico con las instrucciones para restablecer la contraseña.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - email: Correo electrónico del usuario
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de estado
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email   = $request->input('email');
        $handler = new UserHandler();
        $user    = $handler->getUsuarioPorCorreo($email);

        if ($user) {
            $token       = \Illuminate\Support\Str::random(16);
            $hashedToken = Hash::make($token);

            // marca en_recuperacion y actualiza contraseña temporal
            $handler->marcarUsuarioRecuperacion($email, $hashedToken);

            Mail::to($email)->send(new ResetPasswordMail($token));
        }

        return back()->with('status',
            'Si el correo está registrado, recibirás un enlace de recuperación.'
        );
    }

    /**
     * Muestra el formulario para cambiar la contraseña
     * 
     * Este método muestra la vista que contiene el formulario para establecer
     * una nueva contraseña después de que el usuario ha iniciado sesión
     * con credenciales temporales.
     * 
     * @return \Illuminate\View\View Vista con el formulario de cambio de contraseña
     */
    public function showChangePassword()
    {
        return view('change_password');
    }

    /**
     * Procesa la actualización de la contraseña
     * 
     * Este método valida y actualiza la contraseña del usuario actual,
     * desactiva el estado de recuperación y cierra la sesión para que
     * el usuario pueda iniciar sesión con su nueva contraseña.
     * 
     * @param \Illuminate\Http\Request $request Datos de la solicitud:
     *                                         - password: Nueva contraseña
     *                                         - password_confirmation: Confirmación
     * @return \Illuminate\Http\RedirectResponse Redirección al inicio de sesión
     * 
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);
    
        $user      = Auth::user();
        $newHashed = Hash::make($request->input('password'));
    
        // usa email (no correo) porque en tu modelo el campo es 'email'
        $handler = new UserHandler();
        $handler->updateUsuario(
            $user->id,
            $user->email,
            $newHashed,
            $user->rol,
            $user->nombre_completo,
            $user->telefono,
            0
        );
    
        Auth::logout();
    
        return redirect()->route('login.show')
                         ->with('status', 'Tu contraseña ha sido actualizada.');
    }
}