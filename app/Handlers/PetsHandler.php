<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

/**
 * Clase PetsHandler
 * 
 * Esta clase maneja todas las operaciones relacionadas con las mascotas en el sistema.
 * Proporciona métodos para crear, leer, actualizar y eliminar mascotas, así como
 * consultar mascotas por cliente o usuario.
 * 
 * La clase utiliza procedimientos almacenados en la base de datos para realizar
 * todas las operaciones, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Handlers
 */
class PetsHandler
{
    /**
     * @var \PDO Instancia de conexión a la base de datos
     * 
     * Esta propiedad mantiene la conexión activa con la base de datos
     * y es utilizada por todos los métodos de la clase para ejecutar
     * las consultas SQL necesarias.
     */
    protected $pdo;

    /**
     * Constructor de la clase
     * 
     * Inicializa la conexión a la base de datos utilizando la instancia PDO de Laravel.
     * Esta conexión se utiliza para todas las operaciones de base de datos
     * realizadas por los métodos de la clase.
     */
    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    /**
     * Obtiene todas las mascotas del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarMascotas' que retorna
     * un cursor con todas las mascotas registradas en el sistema. El cursor es procesado
     * para convertir los resultados en un array de PHP.
     * 
     * @return array Lista de todas las mascotas. Cada mascota incluye:
     *               - ID de la mascota
     *               - Nombre completo
     *               - Edad
     *               - Peso
     *               - Raza
     *               - Especie
     *               - ID del cliente
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Obtiene todas las mascotas de un cliente específico
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarMascotasPorCliente' para
     * obtener todas las mascotas asociadas a un cliente específico.
     * 
     * @param int $clientId ID del cliente
     * @return array Lista de mascotas del cliente. Cada mascota incluye:
     *               - ID de la mascota
     *               - Nombre completo
     *               - Edad
     *               - Peso
     *               - Raza
     *               - Especie
     *               - ID del cliente
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getPetsByClientId(int $clientId): array
    {
        $sql  = "BEGIN ConsultarMascotasPorCliente(:p_idCliente, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_idCliente', $clientId, \PDO::PARAM_INT);
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

    /**
     * Obtiene todas las mascotas de un usuario específico
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarMascotasPorUsuarioId' para
     * obtener todas las mascotas asociadas a un usuario específico.
     * 
     * @param int $userId ID del usuario
     * @return array Lista de mascotas del usuario. Cada mascota incluye:
     *               - ID de la mascota
     *               - Nombre completo
     *               - Edad
     *               - Peso
     *               - Raza
     *               - Especie
     *               - ID del cliente
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getPetsByUserId(int $userId): array
    {
        $sql  = "BEGIN ConsultarMascotasPorUsuarioId(:p_idUsuario, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_idUsuario', $userId, \PDO::PARAM_INT);
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

    /**
     * Obtiene una mascota específica por su ID
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarMascotaPorId' para
     * obtener los detalles de una mascota específica. Si la mascota no existe, retorna null.
     * 
     * @param int $id ID de la mascota a consultar
     * @return array|null Datos de la mascota si existe, incluyendo:
     *                    - ID de la mascota
     *                    - Nombre completo
     *                    - Edad
     *                    - Peso
     *                    - Raza
     *                    - Especie
     *                    - ID del cliente
     *                    null si no se encuentra la mascota
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Inserta una nueva mascota en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarMascota' para crear
     * una nueva mascota en el sistema. El procedimiento retorna el ID de la nueva mascota
     * creada a través del parámetro de salida.
     * 
     * @param string $nombre_completo Nombre completo de la mascota
     * @param int $edad Edad de la mascota en años
     * @param float $peso Peso de la mascota en kilogramos
     * @param string $raza Raza de la mascota
     * @param string $especie Especie de la mascota (ej: perro, gato, etc.)
     * @param int $idCliente ID del cliente dueño de la mascota
     * @return int ID de la nueva mascota creada
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function insertPet(
        string $nombre_completo,
        int $edad,
        float $peso,
        string $raza,
        string $especie,
        int $idCliente
    ): int
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

    /**
     * Actualiza los datos de una mascota existente
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarMascota' para modificar
     * los datos de una mascota existente. Todos los campos son actualizados con los nuevos
     * valores proporcionados.
     * 
     * @param int $id ID de la mascota a actualizar
     * @param string $nombre_completo Nuevo nombre completo
     * @param int $edad Nueva edad en años
     * @param float $peso Nuevo peso en kilogramos
     * @param string $raza Nueva raza
     * @param string $especie Nueva especie
     * @param int $idCliente Nuevo ID del cliente dueño
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function updatePet(
        int $id,
        string $nombre_completo,
        int $edad,
        float $peso,
        string $raza,
        string $especie,
        int $idCliente
    ): void
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

    /**
     * Elimina una mascota del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarMascotaPorId' para
     * eliminar permanentemente una mascota del sistema. La eliminación es irreversible
     * y también elimina todos los registros asociados a la mascota.
     * 
     * @param int $id ID de la mascota a eliminar
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si el ID no es válido
     */
    public function deletePet(int $id): void
    {
        $sql  = "BEGIN EliminarMascotaPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}