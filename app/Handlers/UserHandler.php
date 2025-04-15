<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    // Inserts a user into the "usuarios" table using the stored procedure and returns the generated user id.
    protected function insertUsuario(string $email, string $password, string $role): int
    {
        $sqlUser = "BEGIN InsertarUsuario(:p_email, :p_password, :p_role, :p_id); END;";
        $stmtUser = $this->pdo->prepare($sqlUser);

        $hashedPassword = Hash::make($password);
        
        $stmtUser->bindParam(':p_email', $email);
        $stmtUser->bindParam(':p_password', $hashedPassword);
        $stmtUser->bindParam(':p_role', $role);

        // Variable for output user id.
        $userId = 0;
        $stmtUser->bindParam(':p_id', $userId, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmtUser->execute();

        return $userId;
    }

    // Inserts client details into the "clientes" table using the stored procedure and returns the generated client id.
    protected function insertCliente(string $name, string $telefono, string $direccion, int $userId): int
    {
        $sqlClient = "BEGIN InsertarCliente(:p_nombreCompleto, :p_telefono, :p_direccion, :p_idUsuario, :p_id); END;";
        $stmtClient = $this->pdo->prepare($sqlClient);

        $stmtClient->bindParam(':p_nombreCompleto', $name);
        $stmtClient->bindParam(':p_telefono', $telefono);
        $stmtClient->bindParam(':p_direccion', $direccion);
        $stmtClient->bindParam(':p_idUsuario', $userId);

        // Variable for output client id.
        $clientId = 0;
        $stmtClient->bindParam(':p_id', $clientId, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmtClient->execute();

        return $clientId;
    }

    // Handles the complete registration of a user and its client details.
    public function registerClient(array $data): array
    {
        $userId = $this->insertUsuario($data['email'], $data['password'], 'cliente');
        $clientId = $this->insertCliente($data['name'], $data['telefono'], $data['direccion'], $userId);

        return [
            'user_id'   => $userId,
            'client_id' => $clientId,
        ];
    }

    // Inserts veterinarian details into the "veterinarios" table using the stored procedure and returns the generated veterinarian id.
    protected function insertVeterinario(string $nombreCompleto, string $fechaInicio, string $telefono, string $especialidad, int $userId): int
    {
        $sqlVeterinario = "BEGIN InsertarVeterinario(:p_nombreCompleto, :p_fechaInicio, :p_telefono, :p_especialidad, :p_idUsuario, :p_id); END;";
        $stmtVeterinario = $this->pdo->prepare($sqlVeterinario);

        $stmtVeterinario->bindParam(':p_nombreCompleto', $nombreCompleto);
        $stmtVeterinario->bindParam(':p_fechaInicio', $fechaInicio);
        $stmtVeterinario->bindParam(':p_telefono', $telefono);
        $stmtVeterinario->bindParam(':p_especialidad', $especialidad);
        $stmtVeterinario->bindParam(':p_idUsuario', $userId);

        // Variable for output veterinarian id.
        $veterinarioId = 0;
        $stmtVeterinario->bindParam(':p_id', $veterinarioId, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmtVeterinario->execute();

        return $veterinarioId;
    }

    // Handles the complete registration of a user and its veterinarian details.
    public function registerVeterinario(array $data): array
    {
        $userId = $this->insertUsuario($data['email'], $data['password'], 'veterinario');
        $veterinarioId = $this->insertVeterinario(
            $data['nombreCompleto'],
            $data['fechaInicio'],
            $data['telefono'],
            $data['especialidad'],
            $userId
        );

        return [
            'user_id'        => $userId,
            'veterinario_id' => $veterinarioId,
        ];
    }
}