<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class InventoryHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    public function getAllInventories(): array
    {
        $sql = "BEGIN ConsultarInventario(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $inventories = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);

            foreach ($row as $key => $value) {
                if ($value instanceof \OCILob) {
                    $row[$key] = $value->load();
                }
            }

            $inventories[] = $row;
        }

        oci_free_statement($cursor);

        return $inventories;
    }

    public function getInventoryById(int $id): ?array
    {
        $sql = "BEGIN ConsultarInventarioPorId(:id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $inventory = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);

        if ($inventory) {
            $inventory = array_change_key_case($inventory, CASE_LOWER);
            foreach ($inventory as $key => $value) {
                if ($value instanceof \OCILob) {
                    $inventory[$key] = $value->load();
                }
            }
        }

        return $inventory ?: null;
    }

    public function insertInventory(string $nombreProducto, string $descripcion, float $precio, int $cantidadUnidades, string $fechaCaducidad): int
    {
        $sql = "BEGIN InsertarInventario(:nombreProducto, :descripcion, :precio, :cantidadUnidades, :fechaCaducidad, :id); END;";
        $stmt = $this->pdo->prepare($sql);

        $id = null;
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidadUnidades', $cantidadUnidades);
        $stmt->bindParam(':fechaCaducidad', $fechaCaducidad);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $id;
    }

    public function updateInventory(int $id, string $nombreProducto, string $descripcion, float $precio, int $cantidadUnidades, string $fechaCaducidad): void
    {
        $sql = "BEGIN ActualizarInventario(:id, :nombreProducto, :descripcion, :precio, :cantidadUnidades, :fechaCaducidad); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidadUnidades', $cantidadUnidades);
        $stmt->bindParam(':fechaCaducidad', $fechaCaducidad);
        $stmt->execute();
    }

    public function deleteInventory(int $id): void
    {
        $sql = "BEGIN EliminarInventarioPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}