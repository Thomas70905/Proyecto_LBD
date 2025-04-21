<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

/**
 * Clase AppointmentHandler
 * 
 * Esta clase maneja todas las operaciones relacionadas con las citas en el sistema.
 * Proporciona métodos para crear, leer, actualizar y eliminar citas, así como
 * funcionalidades adicionales para gestionar productos asociados a las citas.
 * 
 * La clase utiliza procedimientos almacenados en la base de datos para realizar
 * todas las operaciones, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Handlers
 */
class AppointmentHandler
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
     * Obtiene todas las citas del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarCitas' que retorna
     * un cursor con todas las citas registradas en el sistema. El cursor es procesado
     * para convertir los resultados en un array de PHP.
     * 
     * @return array Lista de todas las citas con sus detalles básicos. Cada cita incluye:
     *               - ID de la cita
     *               - ID de la mascota
     *               - Fecha de inicio
     *               - ID del servicio
     *               - Descripción
     *               - Estado de asistencia
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getAllAppointments(): array
    {
        $sql  = "BEGIN ConsultarCitas(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $appointments = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);
            foreach ($row as $key => $value) {
                if ($value instanceof \OCILob) {
                    $row[$key] = $value->load();
                }
            }
            $appointments[] = $row;
        }
        oci_free_statement($cursor);

        return $appointments;
    }

    /**
     * Obtiene una cita específica por su ID
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarCitaPorId' para
     * obtener los detalles de una cita específica. Si la cita no existe, retorna null.
     * 
     * @param int $id ID de la cita a consultar
     * @return array|null Datos de la cita si existe, incluyendo:
     *                    - ID de la cita
     *                    - ID de la mascota
     *                    - Fecha de inicio
     *                    - ID del servicio
     *                    - Descripción
     *                    - Estado de asistencia
     *                    null si no se encuentra la cita
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getAppointmentById(int $id): ?array
    {
        $sql  = "BEGIN ConsultarCitaPorId(:p_id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_id',   $id,     \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $appointment = oci_fetch_assoc($cursor);
        oci_free_statement($cursor);

        if ($appointment) {
            $appointment = array_change_key_case($appointment, CASE_LOWER);
            foreach ($appointment as $key => $value) {
                if ($value instanceof \OCILob) {
                    $appointment[$key] = $value->load();
                }
            }
            return $appointment;
        }
        return null;
    }

    /**
     * Inserta una nueva cita en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarCita' para crear
     * una nueva cita en el sistema. El procedimiento retorna el ID de la nueva cita
     * creada a través del parámetro de salida.
     * 
     * @param int $idMascota ID de la mascota para la cita
     * @param string $fechaInicio Fecha y hora de inicio de la cita en formato YYYY-MM-DD HH:MM:SS
     * @param int $idServicio ID del servicio a realizar
     * @param string $descripcion Descripción detallada de la cita
     * @param int $asistencia Estado de asistencia:
     *                        - 0: No asistió
     *                        - 1: Asistió
     * @return int ID de la nueva cita creada
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function insertAppointment(
        int    $idMascota,
        string $fechaInicio,
        int    $idServicio,
        string $descripcion,
        int    $asistencia
    ): int {
        $sql  = "BEGIN InsertarCita(:p_idMascota, :p_fechaInicio, :p_idServicio, :p_descripcion, :p_asistencia, :p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $newId = null;
        $stmt->bindParam(':p_idMascota',  $idMascota,    \PDO::PARAM_INT);
        $stmt->bindParam(':p_fechaInicio',$fechaInicio);
        $stmt->bindParam(':p_idServicio', $idServicio,   \PDO::PARAM_INT);
        $stmt->bindParam(':p_descripcion',$descripcion);
        $stmt->bindParam(':p_asistencia', $asistencia,   \PDO::PARAM_INT);
        $stmt->bindParam(':p_id',         $newId,        \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);

        $stmt->execute();

        return $newId;
    }

    /**
     * Actualiza los datos de una cita existente
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarCita' para modificar
     * los datos de una cita existente. Todos los campos son actualizados con los nuevos
     * valores proporcionados.
     * 
     * @param int $id ID de la cita a actualizar
     * @param int $idMascota Nuevo ID de mascota
     * @param string $fechaInicio Nueva fecha y hora de inicio en formato YYYY-MM-DD HH:MM:SS
     * @param int $idServicio Nuevo ID de servicio
     * @param string $descripcion Nueva descripción
     * @param int $asistencia Nuevo estado de asistencia:
     *                        - 0: No asistió
     *                        - 1: Asistió
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function updateAppointment(
        int    $id,
        int    $idMascota,
        string $fechaInicio,
        int    $idServicio,
        string $descripcion,
        int    $asistencia
    ): void {
        $sql  = "BEGIN ActualizarCita(:p_id, :p_idMascota, :p_fechaInicio, :p_idServicio, :p_descripcion, :p_asistencia); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id',          $id,           \PDO::PARAM_INT);
        $stmt->bindParam(':p_idMascota',   $idMascota,    \PDO::PARAM_INT);
        $stmt->bindParam(':p_fechaInicio', $fechaInicio);
        $stmt->bindParam(':p_idServicio',  $idServicio,   \PDO::PARAM_INT);
        $stmt->bindParam(':p_descripcion', $descripcion);
        $stmt->bindParam(':p_asistencia',  $asistencia,   \PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Elimina una cita del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarCitaPorId' para
     * eliminar permanentemente una cita del sistema. La eliminación es irreversible
     * y también elimina todos los productos asociados a la cita.
     * 
     * @param int $id ID de la cita a eliminar
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si el ID no es válido
     */
    public function deleteAppointment(int $id): void
    {
        $sql  = "BEGIN EliminarCitaPorId(:p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Obtiene todas las citas con información detallada
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarCitasConDetalles' que
     * retorna un cursor con todas las citas y su información relacionada, incluyendo:
     * - Datos de la mascota (nombre, especie, raza)
     * - Datos del servicio (nombre, descripción, precio)
     * - Datos del cliente (nombre, contacto)
     * - Estado de la cita
     * 
     * @return array Lista de citas con todos sus detalles. Cada cita incluye:
     *               - Datos básicos de la cita
     *               - Información de la mascota
     *               - Detalles del servicio
     *               - Información del cliente
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getAllAppointmentsWithDetails(): array
    {
        $sql  = "BEGIN ConsultarCitasConDetalles(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $appointments = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);
            foreach ($row as $key => $value) {
                if ($value instanceof \OCILob) {
                    $row[$key] = $value->load();
                }
            }
            $appointments[] = $row;
        }
        oci_free_statement($cursor);

        return $appointments;
    }

    /**
     * Obtiene una cita específica con todos sus detalles
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarCitaConDetallesPorId'
     * para obtener una cita específica con toda su información relacionada.
     * 
     * @param int $id ID de la cita a consultar
     * @return array|null Datos completos de la cita si existe, incluyendo:
     *                    - Datos básicos de la cita
     *                    - Información de la mascota
     *                    - Detalles del servicio
     *                    - Información del cliente
     *                    - Productos utilizados
     *                    null si no se encuentra la cita
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getAppointmentWithDetailsById(int $id): ?array
    {
        $sql  = "BEGIN ConsultarCitaConDetallesPorId(:p_id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_id',   $id,     \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $appointment = oci_fetch_assoc($cursor);
        oci_free_statement($cursor);

        if ($appointment) {
            $appointment = array_change_key_case($appointment, CASE_LOWER);
            foreach ($appointment as $key => $value) {
                if ($value instanceof \OCILob) {
                    $appointment[$key] = $value->load();
                }
            }
            return $appointment;
        }
        return null;
    }

    /**
     * Actualiza el estado de asistencia de una cita
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarAsistenciaCita'
     * para modificar el estado de asistencia de una cita específica.
     * 
     * @param int $id ID de la cita
     * @param int $attendance Nuevo estado de asistencia:
     *                        - 0: No asistió
     *                        - 1: Asistió
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si el ID o el estado de asistencia no son válidos
     */
    public function updateAppointmentAttendance(int $id, int $attendance): void
    {
        $sql  = "BEGIN ActualizarAsistenciaCita(:p_id, :p_asistencia); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':p_asistencia', $attendance, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Agrega un producto a una cita
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarProductoUsadoCita'
     * para registrar un producto utilizado durante una cita específica.
     * 
     * @param int $appointmentId ID de la cita
     * @param int $productId ID del producto a agregar
     * @param int $quantity Cantidad del producto utilizada
     * @return int ID del registro de producto agregado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function addProductToAppointment($appointmentId, $productId, $quantity)
    {
        $sql = "BEGIN InsertarProductoUsadoCita(:p_citaId, :p_inventarioId, :p_cantidad, :p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $id = null;
        $stmt->bindParam(':p_citaId', $appointmentId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_inventarioId', $productId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_cantidad', $quantity, \PDO::PARAM_INT);
        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $id;
    }

    /**
     * Actualiza la cantidad de un producto en una cita
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarCantidadProductoCita'
     * para modificar la cantidad de un producto registrado en una cita.
     * 
     * @param int $appointmentId ID de la cita
     * @param int $productId ID del producto
     * @param int $newQuantity Nueva cantidad del producto
     * @return int 1 si la actualización fue exitosa, 0 si falló
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function updateProductQuantity($appointmentId, $productId, $newQuantity)
    {
        $sql = "BEGIN ActualizarCantidadProductoCita(:p_citaId, :p_inventarioId, :p_nuevaCantidad, :p_exito); END;";
        $stmt = $this->pdo->prepare($sql);

        $success = 0;
        $stmt->bindParam(':p_citaId', $appointmentId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_inventarioId', $productId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_nuevaCantidad', $newQuantity, \PDO::PARAM_INT);
        $stmt->bindParam(':p_exito', $success, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $success;
    }

    /**
     * Elimina un producto de una cita
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarProductoDeCita'
     * para remover un producto de los registros de una cita.
     * 
     * @param int $appointmentId ID de la cita
     * @param int $productId ID del producto a eliminar
     * @return int 1 si la eliminación fue exitosa, 0 si falló
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function removeProductFromAppointment($appointmentId, $productId)
    {
        $sql = "BEGIN EliminarProductoDeCita(:p_citaId, :p_inventarioId, :p_exito); END;";
        $stmt = $this->pdo->prepare($sql);

        $success = 0;
        $stmt->bindParam(':p_citaId', $appointmentId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_inventarioId', $productId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_exito', $success, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $success;
    }

    /**
     * Obtiene todos los productos asociados a una cita
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarProductosUsadosCitaPorId'
     * para obtener todos los productos registrados en una cita específica.
     * 
     * @param int $appointmentId ID de la cita
     * @return array Lista de productos usados en la cita, cada producto incluye:
     *               - ID del producto
     *               - Nombre del producto
     *               - Cantidad utilizada
     *               - Precio unitario
     *               - Precio total
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getAppointmentProducts($appointmentId)
    {
        $sql = "BEGIN ConsultarProductosUsadosCitaPorId(:p_citaId, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_citaId', $appointmentId, \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $products = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);
            $products[] = $row;
        }

        oci_free_statement($cursor);
        return $products;
    }

    /**
     * Obtiene todas las citas de un usuario específico con detalles
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarCitasConDetallesPorUsuario'
     * para obtener todas las citas asociadas a un usuario específico, incluyendo
     * información detallada de cada cita.
     * 
     * @param int $userId ID del usuario
     * @return array Lista de citas del usuario con todos sus detalles, incluyendo:
     *               - Datos básicos de la cita
     *               - Información de la mascota
     *               - Detalles del servicio
     *               - Productos utilizados
     *               - Estado de la cita
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getUserAppointmentsWithDetails($userId)
    {
        $sql = "BEGIN ConsultarCitasConDetallesPorUsuario(:p_usuario_id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_usuario_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();
        oci_execute($cursor, OCI_DEFAULT);

        $appointments = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $row = array_change_key_case($row, CASE_LOWER);
            foreach ($row as $key => $value) {
                if ($value instanceof \OCILob) {
                    $row[$key] = $value->load();
                }
            }
            $appointments[] = $row;
        }
        oci_free_statement($cursor);

        return $appointments;
    }
}