<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

/**
 * Clase ServiceHandler
 * 
 * Esta clase maneja todas las operaciones relacionadas con los servicios en el sistema.
 * Proporciona métodos para crear, leer, actualizar y eliminar servicios, así como
 * consultar sus detalles y disponibilidad.
 * 
 * La clase utiliza procedimientos almacenados en la base de datos para realizar
 * todas las operaciones, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Handlers
 */
class ServiceHandler
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
     * Obtiene todos los servicios disponibles
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarServicios' que retorna
     * un cursor con todos los servicios registrados en el sistema. El cursor es procesado
     * para convertir los resultados en un array de PHP.
     * 
     * @return array Lista de todos los servicios. Cada servicio incluye:
     *               - ID del servicio
     *               - Nombre del servicio
     *               - Descripción
     *               - Precio
     *               - Duración estimada
     *               - Estado de disponibilidad
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Obtiene los detalles de un servicio específico
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarServicioPorId' para
     * obtener toda la información detallada de un servicio específico.
     * 
     * @param int $id ID del servicio a consultar
     * @return array Detalles del servicio, incluyendo:
     *               - ID del servicio
     *               - Nombre del servicio
     *               - Descripción detallada
     *               - Precio
     *               - Duración estimada
     *               - Estado de disponibilidad
     *               - Requisitos previos
     *               - Materiales necesarios
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getServiceDetails(int $id): array
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

        return $service ?: [];
    }

    /**
     * Inserta un nuevo servicio
     */
    public function createService(
        string $nombre,
        string $descripcion,
        float  $precio,
        int    $duracion
    ): int {
        $sql  = "BEGIN InsertarServicio(
                    :p_nombre,
                    :p_descripcion,
                    :p_precio,
                    :p_duracion,
                    :p_id
                  ); END;";
        $stmt = $this->pdo->prepare($sql);

        $newId = null;
        $stmt->bindParam(':p_nombre',      $nombre);
        $stmt->bindParam(':p_descripcion', $descripcion);
        $stmt->bindParam(':p_precio',      $precio);
        $stmt->bindParam(':p_duracion',    $duracion, \PDO::PARAM_INT);
        $stmt->bindParam(':p_id',          $newId, \PDO::PARAM_INT| \PDO::PARAM_INPUT_OUTPUT, 32);

        $stmt->execute();

        return $newId;
    }

    /**
     * Actualiza un servicio existente
     */
    public function updateService(
        int    $id,
        string $nombre,
        string $descripcion,
        float  $precio,
        int    $duracion
    ): void {
        $sql  = "BEGIN ActualizarServicio(
                    :p_id,
                    :p_nombre,
                    :p_descripcion,
                    :p_precio,
                    :p_duracion
                  ); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id',          $id,       \PDO::PARAM_INT);
        $stmt->bindParam(':p_nombre',      $nombre);
        $stmt->bindParam(':p_descripcion', $descripcion);
        $stmt->bindParam(':p_precio',      $precio);
        $stmt->bindParam(':p_duracion',    $duracion, \PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Elimina un servicio del sistema
     */
    public function deleteService(int $id): void
    {
        $sql  = "BEGIN EliminarServicioPorId(:p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}