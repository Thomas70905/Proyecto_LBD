<?php
namespace App\Handlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserHandler
{
    protected \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
    }

    /**
     * Llama al SP updateUsuario para modificar email, password y estado de recuperación.
     */
    public function updateUsuario(
        int    $id,
        string $email,
        string $password,
        string $rol,
        string $nombre_completo,
        string $telefono,
        int    $enRecuperacion
    ): void {
        $sql = "
            BEGIN
              ActualizarUsuario(
                :p_id,
                :p_email,
                :p_password,
                :p_rol,
                :p_nombre_completo,
                :p_telefono,
                :p_en_recuperacion
              );
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_id',              $id,             \PDO::PARAM_INT);
        $stmt->bindParam(':p_email',           $email);
        $stmt->bindParam(':p_password',        $password);
        $stmt->bindParam(':p_rol',             $rol);
        $stmt->bindParam(':p_nombre_completo', $nombre_completo);
        $stmt->bindParam(':p_telefono',        $telefono);
        $stmt->bindParam(':p_en_recuperacion', $enRecuperacion, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Inserta un usuario y devuelve su id.
     */
    protected function insertUsuario(
        string $email,
        string $password,
        string $role,
        string $nombre_completo,
        string $telefono,
        int    $enRecuperacion = 0
    ): int {
        $sql = "
            BEGIN
              InsertarUsuario(
                :p_email,
                :p_password,
                :p_rol,
                :p_nombre_completo,
                :p_telefono,
                :p_en_recuperacion,
                :p_id
              );
            END;
        ";
        $stmt   = $this->pdo->prepare($sql);
        $hashed = Hash::make($password);

        $stmt->bindParam(':p_email',           $email);
        $stmt->bindParam(':p_password',        $hashed);
        $stmt->bindParam(':p_rol',             $role);
        $stmt->bindParam(':p_nombre_completo', $nombre_completo);
        $stmt->bindParam(':p_telefono',        $telefono);
        $stmt->bindParam(':p_en_recuperacion', $enRecuperacion, \PDO::PARAM_INT);

        $userId = 0;
        $stmt->bindParam(':p_id', $userId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $userId;
    }

    // Inserta datos de cliente en "clientes" y devuelve su id
    protected function insertCliente(string $direccion, int $userId): int
    {
        $sql = "
            BEGIN
              InsertarCliente(
                :p_direccion,
                :p_idUsuario,
                :p_id
              );
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_direccion', $direccion);
        $stmt->bindParam(':p_idUsuario', $userId);

        $clientId = 0;
        $stmt->bindParam(':p_id', $clientId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $clientId;
    }

    // Inserta datos del veterinario en "veterinarios" y devuelve su id
    protected function insertVeterinario(int $userId): int
    {
        $sql = "
            BEGIN
              InsertarVeterinario(
                :p_idUsuario,
                :p_id
              );
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_idUsuario', $userId);

        $vetId = 0;
        $stmt->bindParam(':p_id', $vetId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $vetId;
    }

    // Inserta datos del administrador en "administradores" y devuelve su id
    protected function insertAdministrador(int $userId): int
    {
        $sql = "
            BEGIN
              InsertarAdministrador(
                :p_idUsuario,
                :p_id
              );
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_idUsuario', $userId);

        $adminId = 0;
        $stmt->bindParam(':p_id', $adminId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);
        $stmt->execute();

        return $adminId;
    }

    // Registro completo de un cliente
    public function registerClient(array $data): array
    {
        $userId   = $this->insertUsuario(
            $data['email'],
            $data['password'],
            'cliente',
            $data['nombre_completo'],
            $data['telefono']
        );
        $clientId = $this->insertCliente($data['direccion'], $userId);

        return [
            'user_id'   => $userId,
            'client_id' => $clientId,
        ];
    }

    // Registro completo
    public function registerVeterinario(array $data): array
    {
        $userId        = $this->insertUsuario(
            $data['email'],
            $data['password'],
            'veterinario',
            $data['nombre_completo'],
            $data['telefono']
        );
        $veterinarioId = $this->insertVeterinario($userId);

        return [
            'user_id'        => $userId,
            'veterinario_id' => $veterinarioId,
        ];
    }

    // Registro completo de un administrador
    public function registerAdministrador(array $data): array
    {
        $userId  = $this->insertUsuario(
            $data['email'],
            $data['password'],
            'administrador',
            $data['nombre_completo'],
            $data['telefono']
        );
        $adminId = $this->insertAdministrador($userId);

        return [
            'user_id'  => $userId,
            'admin_id' => $adminId,
        ];
    }

    /**
     * Obtiene todos los empleados (veterinarios y administradores)
     * usando el SP ConsultarEmpleados.
     */
    public function getEmpleados(): array
    {
        $sql  = "BEGIN ConsultarEmpleados(:cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);

        $empleados = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $empleados[] = array_change_key_case($row, CASE_LOWER);
        }

        oci_free_statement($cursor);

        return $empleados;
    }

    /**
     * Obtiene un usuario por correo usando SP ConsultarUsuarioPorEmail.
     */
    public function getUsuarioPorCorreo(string $email): ?array
    {
        $sql  = "BEGIN ConsultarUsuarioPorEmail(:p_email, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);

        $cursor = null;
        $stmt->bindParam(':p_email', $email);
        $stmt->bindParam(':cursor',  $cursor, \PDO::PARAM_STMT);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        $usuario = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);

        return $usuario
            ? array_change_key_case($usuario, CASE_LOWER)
            : null;
    }

    /**
     * Marca en_recuperacion y actualiza contraseña temporal usando SP
     * MarcarUsuarioRecuperacionContrasenaPorCorreo(p_email, p_password_temp)
     */
    public function marcarUsuarioRecuperacion(string $email, string $tempPassword): void
    {
        $sql = "
            BEGIN
              MarcarUsuarioRecuperacionContrasenaPorCorreo(
                :p_email,
                :p_password_temp
              );
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_email',         $email);
        $stmt->bindParam(':p_password_temp', $tempPassword);
        $stmt->execute();
    }
}