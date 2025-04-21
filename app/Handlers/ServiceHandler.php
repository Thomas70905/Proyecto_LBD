<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

/**
 * Manejador de servicios
 * 
 * Esta clase se encarga de gestionar todas las operaciones relacionadas
 * con los servicios ofrecidos por la clínica veterinaria. Proporciona
 * métodos para:
 * 
 * 1. Gestión de servicios:
 *    - Creación de nuevos servicios
 *    - Actualización de información
 *    - Eliminación de servicios
 *    - Consulta de servicios disponibles
 * 
 * 2. Operaciones de base de datos:
 *    - Ejecución de procedimientos almacenados
 *    - Manejo de transacciones
 *    - Validación de datos
 * 
 * 3. Integración:
 *    - Conexión con la base de datos
 *    - Interacción con otros handlers
 *    - Manejo de errores
 * 
 * @package App\Handlers
 */
class ServiceHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    /**
     * Obtiene todos los servicios disponibles.
     * 
     * Este método ejecuta el procedimiento almacenado para obtener
     * todos los servicios registrados en el sistema.
     * 
     * @return array Lista de servicios
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
     * Obtiene los detalles de un servicio específico.
     * 
     * Este método ejecuta el procedimiento almacenado para obtener
     * toda la información detallada de un servicio específico.
     * 
     * @param int $id ID del servicio
     * @return array Detalles del servicio
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
     * Crea un nuevo servicio en el sistema.
     * 
     * Este método ejecuta el procedimiento almacenado para crear
     * un nuevo servicio con toda su información.
     * 
     * @param array $data Datos del servicio
     * @return array Resultado de la operación
     */
    public function createService(array $data): array
    {
        return DB::select('CALL sp_create_service(?, ?, ?, ?)', [
            $data['name'],
            $data['description'],
            $data['price'],
            $data['duration']
        ]);
    }

    /**
     * Actualiza la información de un servicio existente.
     * 
     * Este método ejecuta el procedimiento almacenado para actualizar
     * la información de un servicio específico.
     * 
     * @param int $id ID del servicio
     * @param array $data Nuevos datos del servicio
     * @return array Resultado de la operación
     */
    public function updateService(int $id, array $data): array
    {
        return DB::select('CALL sp_update_service(?, ?, ?, ?, ?)', [
            $id,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['duration']
        ]);
    }

    /**
     * Elimina un servicio del sistema.
     * 
     * Este método ejecuta el procedimiento almacenado para eliminar
     * un servicio específico y todos sus registros relacionados.
     * 
     * @param int $id ID del servicio
     * @return array Resultado de la operación
     */
    public function deleteService(int $id): array
    {
        return DB::select('CALL sp_delete_service(?)', [$id]);
    }
}