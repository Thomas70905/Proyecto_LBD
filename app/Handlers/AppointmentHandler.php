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
}