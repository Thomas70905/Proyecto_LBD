<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Handlers\UserHandler;
use Exception;
use Illuminate\Support\Facades\Log;

class SignInController extends Controller
{
    protected $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
    }

    // Muestra el formulario de registro (signin)
    public function show()
    {
        return view('signin');
    }

    // Procesa el registro de un nuevo usuario usando UserHandler para llamar a los stored procedures
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