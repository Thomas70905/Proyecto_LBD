<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|numeric',
            'email' => 'required|email|unique:clientes,email',
            'direccion' => 'nullable|string|max:255'
        ]);

        DB::statement(
            'BEGIN sp_insertar_cliente(:nombre, :apellido, :telefono, :email, :direccion); END;',
            [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion
            ]
        );

        return response()->json(['message' => 'Cliente creado exitosamente']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|numeric',
            'email' => 'required|email|unique:clientes,email,'.$id.',clienteid',
            'direccion' => 'nullable|string|max:255'
        ]);

        DB::statement(
            'BEGIN sp_actualizar_cliente(:cliente_id, :nombre, :apellido, :telefono, :email, :direccion); END;',
            [
                'cliente_id' => $id,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion
            ]
        );

        return response()->json(['message' => 'Cliente actualizado exitosamente']);
    }

    public function destroy($id)
    {
        DB::statement(
            'BEGIN sp_eliminar_cliente(:cliente_id); END;',
            ['cliente_id' => $id]
        );

        return response()->json(['message' => 'Cliente eliminado exitosamente']);
    }
}