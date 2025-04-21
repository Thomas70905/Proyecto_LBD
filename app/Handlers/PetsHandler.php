<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class PetsHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    public function getAllPets(): array
    {
        $sql  = "BEGIN ConsultarMascotas(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $pets = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);
            foreach ($row as $k => $v) {
                if ($v instanceof \OCILob) {
                    $row[$k] = $v->load();
                }
            }
            $pets[] = $row;
        }
        oci_free_statement($cursor);

        return $pets;
    }

    public function getPetById(int $id): ?array
    {
        $sql  = "BEGIN ConsultarMascotaPorId(:id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':id',     $id,      \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor,  \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $pet = oci_fetch_assoc($cursor);
        oci_free_statement($cursor);

        if ($pet) {
            $pet = array_change_key_case($pet, CASE_LOWER);
            foreach ($pet as $k => $v) {
                if ($v instanceof \OCILob) {
                    $pet[$k] = $v->load();
                }
            }
            return $pet;
        }
        return null;
    }

    public function insertPet(string $nombre_completo, int $edad, float $peso, string $raza, string $especie, int $idCliente): int
    {
        $sql  = "BEGIN InsertarMascota(:nombre, :edad, :peso, :raza, :especie, :idCliente, :outId); END;";
        $stmt = $this->pdo->prepare($sql);

        $outId = null;
        $stmt->bindParam(':nombre',     $nombre_completo);
        $stmt->bindParam(':edad',       $edad);
        $stmt->bindParam(':peso',       $peso);
        $stmt->bindParam(':raza',       $raza);
        $stmt->bindParam(':especie',    $especie);
        $stmt->bindParam(':idCliente',  $idCliente);
        $stmt->bindParam(':outId',      $outId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $outId;
    }

    public function updatePet(int $id, string $nombre_completo, int $edad, float $peso, string $raza, string $especie, int $idCliente): void
    {
        $sql  = "BEGIN ActualizarMascota(:id, :nombre, :edad, :peso, :raza, :especie, :idCliente); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id',         $id);
        $stmt->bindParam(':nombre',     $nombre_completo);
        $stmt->bindParam(':edad',       $edad);
        $stmt->bindParam(':peso',       $peso);
        $stmt->bindParam(':raza',       $raza);
        $stmt->bindParam(':especie',    $especie);
        $stmt->bindParam(':idCliente',  $idCliente);
        $stmt->execute();
    }

    public function deletePet(int $id): void
    {
        $sql  = "BEGIN EliminarMascotaPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}