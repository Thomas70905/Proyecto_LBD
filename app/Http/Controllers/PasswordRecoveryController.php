<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

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
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        $user  = Usuario::where('email', $email)->first();

        if ($user) {
            // Genera un token breve
            $token = Str::random(16);

            // TODO aqui por medio de un proceso almacenado actualizar la contraseña
            // del usuario a la contraseña generada y actualizar el estado a recuperacion


            // Envía el correo con el enlace de recuperación usando la Mailable
            Mail::to($email)->send(new ResetPasswordMail($token));
        }

        return back()->with('status', 
            'Si el correo está registrado, recibirás un enlace de recuperación.'
        );
    }
}