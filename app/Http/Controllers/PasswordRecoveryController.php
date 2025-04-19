<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\ResetPasswordMail;
use App\Handlers\UserHandler;

class PasswordRecoveryController extends Controller
{
    // Muestra el formulario de recuperación
    public function show()
    {
        return view('password_recovery');
    }

    // Procesa el envío del enlace de restablecimiento
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

    // Muestra el formulario para cambiar contraseña después de login
    public function showChangePassword()
    {
        return view('change_password');
    }

    // Procesa la actualización de la nueva contraseña
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