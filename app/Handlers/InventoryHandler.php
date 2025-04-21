<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

/**
 * Clase InventoryHandler
 * 
 * Esta clase maneja todas las operaciones relacionadas con el inventario del sistema.
 * Proporciona métodos para crear, leer, actualizar y eliminar productos del inventario,
 * así como consultar su estado y disponibilidad.
 * 
 * La clase utiliza procedimientos almacenados en la base de datos para realizar
 * todas las operaciones, asegurando la integridad y consistencia de los datos.
 * 
 * @package App\Handlers
 */
class InventoryHandler
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
     * Obtiene todos los productos del inventario
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarInventario' que retorna
     * un cursor con todos los productos registrados en el sistema. El cursor es procesado
     * para convertir los resultados en un array de PHP.
     * 
     * @return array Lista de todos los productos del inventario. Cada producto incluye:
     *               - ID del producto
     *               - Nombre del producto
     *               - Descripción
     *               - Precio
     *               - Cantidad de unidades disponibles
     *               - Fecha de caducidad
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Obtiene un producto específico del inventario por su ID
     * 
     * Este método ejecuta el procedimiento almacenado 'ConsultarInventarioPorId' para
     * obtener los detalles de un producto específico. Si el producto no existe, retorna null.
     * 
     * @param int $id ID del producto a consultar
     * @return array|null Datos del producto si existe, incluyendo:
     *                    - ID del producto
     *                    - Nombre del producto
     *                    - Descripción
     *                    - Precio
     *                    - Cantidad de unidades disponibles
     *                    - Fecha de caducidad
     *                    null si no se encuentra el producto
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     */
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

    /**
     * Inserta un nuevo producto en el inventario
     * 
     * Este método ejecuta el procedimiento almacenado 'InsertarInventario' para crear
     * un nuevo producto en el sistema. El procedimiento retorna el ID del nuevo producto
     * creado a través del parámetro de salida.
     * 
     * @param string $nombreProducto Nombre del producto
     * @param string $descripcion Descripción detallada del producto
     * @param float $precio Precio unitario del producto
     * @param int $cantidadUnidades Cantidad inicial de unidades disponibles
     * @param string $fechaCaducidad Fecha de caducidad del producto en formato YYYY-MM-DD
     * @return int ID del nuevo producto creado
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function insertInventory(
        string $nombreProducto,
        string $descripcion,
        float $precio,
        int $cantidadUnidades,
        string $fechaCaducidad
    ): int
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

    /**
     * Actualiza los datos de un producto existente en el inventario
     * 
     * Este método ejecuta el procedimiento almacenado 'ActualizarInventario' para modificar
     * los datos de un producto existente. Todos los campos son actualizados con los nuevos
     * valores proporcionados.
     * 
     * @param int $id ID del producto a actualizar
     * @param string $nombreProducto Nuevo nombre del producto
     * @param string $descripcion Nueva descripción
     * @param float $precio Nuevo precio unitario
     * @param int $cantidadUnidades Nueva cantidad de unidades disponibles
     * @param string $fechaCaducidad Nueva fecha de caducidad en formato YYYY-MM-DD
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si los parámetros no son válidos
     */
    public function updateInventory(
        int $id,
        string $nombreProducto,
        string $descripcion,
        float $precio,
        int $cantidadUnidades,
        string $fechaCaducidad
    ): void
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

    /**
     * Elimina un producto del inventario
     * 
     * Este método ejecuta el procedimiento almacenado 'EliminarInventarioPorId' para
     * eliminar permanentemente un producto del sistema. La eliminación es irreversible
     * y también elimina todos los registros asociados al producto.
     * 
     * @param int $id ID del producto a eliminar
     * 
     * @throws \PDOException Si ocurre un error al ejecutar la consulta
     * @throws \InvalidArgumentException Si el ID no es válido
     */
    public function deleteInventory(int $id): void
    {
        $sql = "BEGIN EliminarInventarioPorId(:id); END;";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}