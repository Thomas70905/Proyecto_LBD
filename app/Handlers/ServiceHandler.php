<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class ServiceHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    public function getAllServices(): array
    {
        $sql = "BEGIN ConsultarServicios(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $services = [];
        while ($row = oci_fetch_assoc($cursor)) {
            // Normalize keys to lowercase
            $row = array_change_key_case($row, CASE_LOWER);

            // Convert OCILob objects to strings
            foreach ($row as $key => $value) {
                if ($value instanceof \OCILob) {
                    $row[$key] = $value->load(); // Use load() to fetch the full content of the CLOB/BLOB
                }
            }

            $services[] = $row;
        }

        oci_free_statement($cursor);

        return $services;
    }

    public function getServiceById(int $id): ?array
    {
        $sql = "BEGIN ConsultarServicioPorId(:id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $service = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);

        // Normalize keys to lowercase and handle OCILob objects
        if ($service) {
            $service = array_change_key_case($service, CASE_LOWER);
            foreach ($service as $key => $value) {
                if ($value instanceof \OCILob) {
                    $service[$key] = $value->load(); // Use load() to fetch the full content of the CLOB/BLOB
                }
            }
        }

        return $service ?: null;
    }

    public function insertService(string $name, string $description, float $cost, int $duration): int
    {
        $sql = "BEGIN InsertarServicio(:name, :description, :cost, :duration, :id); END;";
        $stmt = $this->pdo->prepare($sql);

        $id = null;
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':cost', $cost);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $id;
    }

    public function updateService(int $id, string $name, string $description, float $cost, int $duration): void
    {
        $sql = "BEGIN ActualizarServicio(:id, :name, :description, :cost, :duration); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':cost', $cost);
        $stmt->bindParam(':duration', $duration);
        $stmt->execute();
    }

    public function deleteService(int $id): void
    {
        $sql = "BEGIN EliminarServicioPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}