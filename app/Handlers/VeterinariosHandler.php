<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class VeterinariosHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    // Fetch all veterinarios using the stored procedure
    public function getAllVeterinarios(): array
    {
        $sql = "BEGIN ConsultarVeterinarios(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $veterinarios = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $veterinarios[] = array_change_key_case($row, CASE_LOWER);
        }

        oci_free_statement($cursor);

        return $veterinarios;
    }

    // Fetch a single veterinario by ID using the stored procedure
    public function getVeterinarioById(int $id): ?array
    {
        $sql = "BEGIN ConsultarVeterinarioPorId(:id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $veterinario = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);

        return $veterinario ? array_change_key_case($veterinario, CASE_LOWER) : null;
    }

    // Insert a new veterinario using the stored procedure
    public function insertVeterinario(array $data): int
    {
        $sql = "BEGIN InsertarVeterinario(:nombreCompleto, :fechaInicio, :telefono, :especialidad, :idUsuario, :id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nombreCompleto', $data['nombreCompleto']);
        $stmt->bindParam(':fechaInicio', $data['fechaInicio']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':especialidad', $data['especialidad']);
        $stmt->bindParam(':idUsuario', $data['idUsuario']);

        $id = null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $id;
    }

    // Update an existing veterinario using the stored procedure
    public function updateVeterinario(int $id, array $data): void
    {
        $sql = "BEGIN ActualizarVeterinario(:id, :nombreCompleto, :fechaInicio, :telefono, :especialidad, :idUsuario); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombreCompleto', $data['nombreCompleto']);
        $stmt->bindParam(':fechaInicio', $data['fechaInicio']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':especialidad', $data['especialidad']);
        $stmt->bindParam(':idUsuario', $data['idUsuario']);
        $stmt->execute();
    }

    // Delete a veterinario by ID using the stored procedure
    public function deleteVeterinario(int $id): void
    {
        $sql = "BEGIN EliminarVeterinarioPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}