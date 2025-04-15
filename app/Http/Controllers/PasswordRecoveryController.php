<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class PasswordRecoveryController extends Controller
{
    // Muestra el formulario de recuperación
    public function show()
    {
        return view('password_recover');
    }

    // Procesa el envío del enlace de restablecimiento
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        // Verifica si el usuario existe, pero no se informa al solicitante
        $user = Usuario::where('email', $email)->first();

        if ($user) {
            // Genera un token de restablecimiento
            $token = Str::random(60);

            // Almacena el token en la tabla "password_resets"
            DB::table('password_resets')
                ->updateOrInsert(
                    ['email' => $email],
                    ['token' => $token, 'created_at' => now()]
                );

            // Envía el correo con el enlace de restablecimiento usando la clase Mailable
            Mail::to($email)->send(new ResetPasswordMail($token));
        }

        // Siempre muestra el mismo mensaje, sin importar si el usuario existe o no
        return redirect()->back()->with('status', 'Si el correo suministrado está registrado, se ha enviado un enlace de recuperación a su correo.');
    }
}