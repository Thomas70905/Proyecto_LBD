<?php
namespace App\Handlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Clase UserHandler
 * 
 * Esta clase maneja todas las operaciones relacionadas con los usuarios en el sistema.
 * Proporciona métodos para gestionar usuarios, clientes, veterinarios y administradores,
 * incluyendo operaciones de registro, actualización, consulta y eliminación.
 * 
 * La clase utiliza procedimientos almacenados en la base de datos para realizar
 * todas las operaciones, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Handlers
 */
class UserHandler
{
    /**
     * @var \PDO Instancia de conexión a la base de datos
     * 
     * Esta propiedad mantiene la conexión activa con la base de datos
     * y es utilizada por todos los métodos de la clase para ejecutar
     * las consultas SQL necesarias.
     */
    protected \PDO $pdo;

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
     * Actualiza la información de un usuario existente
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarUsuario' para modificar
     * los datos de un usuario específico.
     * 
     * @param int $id ID del usuario a actualizar
     * @param string $email Nuevo correo electrónico
     * @param string $password Nueva contraseña (sin hash)
     * @param string $rol Nuevo rol del usuario
     * @param string $nombre_completo Nuevo nombre completo
     * @param string $telefono Nuevo número de teléfono
     * @param int $enRecuperacion Estado de recuperación de contraseña (0 o 1)
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
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
     * Inserta un nuevo usuario en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarUsuario' para crear
     * un nuevo usuario con toda su información.
     * 
     * @param string $email Correo electrónico del usuario
     * @param string $password Contraseña sin hash
     * @param string $role Rol del usuario
     * @param string $nombre_completo Nombre completo
     * @param string $telefono Número de teléfono
     * @param int $enRecuperacion Estado de recuperación (0 por defecto)
     * @return int ID del usuario creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
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

    /**
     * Inserta un nuevo cliente en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarCliente' para crear
     * un nuevo cliente asociado a un usuario existente.
     * 
     * @param string $direccion Dirección del cliente
     * @param int $userId ID del usuario asociado
     * @return int ID del cliente creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Inserta un nuevo veterinario en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarVeterinario' para crear
     * un nuevo veterinario asociado a un usuario existente.
     * 
     * @param int $userId ID del usuario asociado
     * @return int ID del veterinario creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Inserta un nuevo administrador en el sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarAdministrador' para crear
     * un nuevo administrador asociado a un usuario existente.
     * 
     * @param int $userId ID del usuario asociado
     * @return int ID del administrador creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Registra un nuevo cliente en el sistema
     * 
     * Este método realiza el registro completo de un cliente, creando tanto el usuario
     * como el registro de cliente asociado.
     * 
     * @param array $data Datos del cliente:
     *                    - email: Correo electrónico
     *                    - password: Contraseña
     *                    - nombre_completo: Nombre completo
     *                    - telefono: Número de teléfono
     *                    - direccion: Dirección
     * @return array Resultado del registro:
     *               - user_id: ID del usuario creado
     *               - client_id: ID del cliente creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Registra un nuevo veterinario en el sistema
     * 
     * Este método realiza el registro completo de un veterinario, creando tanto el usuario
     * como el registro de veterinario asociado.
     * 
     * @param array $data Datos del veterinario:
     *                    - email: Correo electrónico
     *                    - password: Contraseña
     *                    - nombre_completo: Nombre completo
     *                    - telefono: Número de teléfono
     * @return array Resultado del registro:
     *               - user_id: ID del usuario creado
     *               - veterinario_id: ID del veterinario creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Registra un nuevo administrador en el sistema
     * 
     * Este método realiza el registro completo de un administrador, creando tanto el usuario
     * como el registro de administrador asociado.
     * 
     * @param array $data Datos del administrador:
     *                    - email: Correo electrónico
     *                    - password: Contraseña
     *                    - nombre_completo: Nombre completo
     *                    - telefono: Número de teléfono
     * @return array Resultado del registro:
     *               - user_id: ID del usuario creado
     *               - admin_id: ID del administrador creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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
     * Obtiene todos los empleados del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarEmpleados' para obtener
     * una lista de todos los empleados (veterinarios y administradores) registrados.
     * 
     * @return array Lista de empleados, cada uno incluye:
     *               - ID del empleado
     *               - Nombre completo
     *               - Correo electrónico
     *               - Teléfono
     *               - Rol
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
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
     * Obtiene un usuario por su correo electrónico
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarUsuarioPorCorreo' para
     * obtener los datos de un usuario específico.
     * 
     * @param string $email Correo electrónico del usuario
     * @return array|null Datos del usuario si existe, null si no se encuentra
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getUsuarioPorCorreo(string $email): ?array
    {
        $sql  = "BEGIN ConsultarUsuarioPorCorreo(:p_email, :cursor); END;";
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
     * Obtiene un usuario por su ID
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarUsuarioPorId' para
     * obtener los datos de un usuario específico.
     * 
     * @param int $id ID del usuario
     * @return array|null Datos del usuario si existe, null si no se encuentra
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getUsuarioPorId(int $id): ?array
    {
        $sql = "BEGIN ConsultarUsuarioPorId(:p_id, :cursor); END;";
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);

        $cursor = null;
        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);
        
        $stmt->execute();
        
        oci_execute($cursor, OCI_DEFAULT);

        $usuario = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);

        return $usuario ? array_change_key_case($usuario, CASE_LOWER) : null;
    }

    /**
     * Obtiene el ID de cliente asociado a un ID de usuario
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarClienteIdPorIdUsuario'
     * para obtener el ID de cliente asociado a un usuario específico.
     * 
     * @param int $idUsuario ID del usuario
     * @return int|null ID del cliente si existe, null si no se encuentra
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function getClienteIdPorUsuarioId(int $idUsuario): ?int
    {
        $sql  = "BEGIN ConsultarClienteIdPorIdUsuario(:p_idUsuario, :p_id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':p_idUsuario', $idUsuario, \PDO::PARAM_INT);

        $clienteId = null;
        $stmt->bindParam(':p_id', $clienteId, \PDO::PARAM_INT|\PDO::PARAM_INPUT_OUTPUT, 32);

        $stmt->execute();

        return $clienteId;
    }

    /**
     * Elimina un usuario del sistema
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarUsuarioPorId' para
     * eliminar un usuario y todos sus registros asociados.
     * 
     * @param int $id ID del usuario a eliminar
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function deleteUsuario(int $id): void
    {
        $sql = "
            BEGIN
                EliminarUsuarioPorId(:p_id);
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Elimina un veterinario por su ID de usuario
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarVeterinarioPorUsuarioId'
     * para eliminar un registro de veterinario específico.
     * 
     * @param int $userId ID del usuario asociado al veterinario
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function deleteVeterinarioPorUsuarioId(int $userId): void
    {
        $sql = "
            BEGIN
                EliminarVeterinarioPorUsuarioId(:p_id);
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Elimina un administrador por su ID de usuario
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarAdministradorPorUsuarioId'
     * para eliminar un registro de administrador específico.
     * 
     * @param int $userId ID del usuario asociado al administrador
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
    public function deleteAdministradorPorUsuarioId(int $userId): void
    {
        $sql = "
            BEGIN
                EliminarAdministradorPorUsuarioId(:p_id);
            END;
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':p_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Marca un usuario para recuperación de contraseña
     * 
     * Este método ejecuta el procedimiento almacenado 'MarcarUsuarioRecuperacionContrasenaPorCorreo'
     * para marcar un usuario como en proceso de recuperación de contraseña.
     * 
     * @param string $email Correo electrónico del usuario
     * @param string $tempPassword Contraseña temporal
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
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