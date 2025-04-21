<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class AppointmentHandler
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

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

    public function deleteAppointment(int $id): void
    {
        $sql  = "BEGIN EliminarCitaPorId(:p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

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

    public function updateAppointmentAttendance(int $id, int $attendance): void
    {
        $sql  = "BEGIN ActualizarAsistenciaCita(:p_id, :p_asistencia); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':p_asistencia', $attendance, \PDO::PARAM_INT);
        $stmt->execute();
    }

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