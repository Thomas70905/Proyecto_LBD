<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migración CreateProductosUsadosCitaTable
 *
 * Esta migración crea la tabla 'productos_usados_cita' con las siguientes columnas:
 * - id: NUMBER generado como identidad (PRIMARY KEY)
 * - citaId: NUMBER NOT NULL (FOREIGN KEY a citas.id)
 * - inventarioId: NUMBER NOT NULL (FOREIGN KEY a inventario.id)
 * - cantidad: NUMBER NOT NULL
 *
 * Define la función y procedimientos almacenados:
 * - DescontarUnidadesProducto: función para descontar stock y retornar la nueva cantidad
 * - ConsultarProductosUsadosCitaPorId: devuelve productos usados de una cita
 * - AgregarProductoACita: función para insertar o actualizar productos en una cita
 * - InsertarProductoUsadoCita: procedimiento para agregar producto y descontar stock
 * - EliminarProductosUsadosCitaPorId: elimina productos usados de una cita
 * - ObtenerCantidadProductoCita: función para obtener cantidad de un producto en una cita
 * - ActualizarCantidadInventario: función para ajustar stock de inventario
 * - ActualizarCantidadEnCita: función para actualizar cantidad en la cita
 * - ActualizarCantidadProducto: función para gestionar cambios de cantidad en la cita
 */
return new class extends Migration {
    public function up(): void
    {
        // Crea la tabla productos_usados_cita (solo referencia)
        DB::unprepared("
            CREATE TABLE productos_usados_cita (
                id             NUMBER GENERATED BY DEFAULT ON NULL AS IDENTITY PRIMARY KEY,
                citaId         NUMBER NOT NULL,
                inventarioId   NUMBER NOT NULL,
                cantidad       NUMBER NOT NULL,
                CONSTRAINT fk_puc_cita       FOREIGN KEY (citaId)       REFERENCES citas(id),
                CONSTRAINT fk_puc_inventario FOREIGN KEY (inventarioId) REFERENCES inventario(id)
            )
        ");

        // Función para descontar unidades del inventario y devolver el nuevo stock
        DB::unprepared("
            CREATE OR REPLACE FUNCTION DescontarUnidadesProducto(
                p_inventarioId IN NUMBER,
                p_cantidad     IN NUMBER
            ) RETURN NUMBER IS
                v_nuevaCantidadProducto NUMBER;
            BEGIN
                UPDATE inventario
                   SET cantidadUnidades = cantidadUnidades - p_cantidad
                 WHERE id = p_inventarioId
                 RETURNING cantidadUnidades INTO v_nuevaCantidadProducto;
                RETURN v_nuevaCantidadProducto;
            END DescontarUnidadesProducto;
        ");

        // SP: consultar productos usados en una cita
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarProductosUsadosCitaPorId(
                p_citaId IN NUMBER,
                cur      OUT SYS_REFCURSOR
            ) IS
            BEGIN
                OPEN cur FOR
                SELECT 
                    puc.id,
                    puc.citaId,
                    puc.inventarioId,
                    i.nombreproducto,
                    i.precio,
                    i.cantidadunidades,
                    puc.cantidad
                FROM productos_usados_cita puc
                JOIN inventario i ON puc.inventarioId = i.id
                WHERE puc.citaId = p_citaId;
            END;
        ");

        // SP: insertar producto usado en cita y descontar stock con la función
        DB::unprepared("
            CREATE OR REPLACE FUNCTION AgregarProductoACita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_cantidad     IN NUMBER
            ) RETURN NUMBER IS
                v_id NUMBER;
                v_existe NUMBER;
            BEGIN
                -- Verifica si el producto ya existe en la cita
                SELECT COUNT(*)
                INTO v_existe
                FROM productos_usados_cita
                WHERE citaId = p_citaId
                AND inventarioId = p_inventarioId;

                IF v_existe > 0 THEN
                    -- Si existe, actualiza la cantidad
                    UPDATE productos_usados_cita
                    SET cantidad = cantidad + p_cantidad
                    WHERE citaId = p_citaId
                    AND inventarioId = p_inventarioId
                    RETURNING id INTO v_id;
                ELSE
                    -- Si no existe, crea un nuevo registro
                    INSERT INTO productos_usados_cita (citaId, inventarioId, cantidad)
                    VALUES (p_citaId, p_inventarioId, p_cantidad)
                    RETURNING id INTO v_id;
                END IF;

                RETURN v_id;
            END;
        ");

        // SP: insertar producto usado en cita y descontar stock con la función
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE InsertarProductoUsadoCita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_cantidad     IN NUMBER,
                p_id           OUT NUMBER
            ) IS
                v_cantidadProducto NUMBER;
            BEGIN
                -- Usa la función para agregar/actualizar el producto
                p_id := AgregarProductoACita(p_citaId, p_inventarioId, p_cantidad);

                -- Descuenta las unidades del inventario
                v_cantidadProducto := DescontarUnidadesProducto(p_inventarioId, p_cantidad);

                COMMIT;
            END;
        ");

        // SP: elimina registros de productos usados en una cita
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE EliminarProductosUsadosCitaPorId(
                p_citaId IN NUMBER
            ) IS
            BEGIN
                DELETE FROM productos_usados_cita
                 WHERE citaId = p_citaId;
                COMMIT;
            END;
        ");

        // Función para obtener cantidad actual de un producto en una cita
        DB::unprepared("
            CREATE OR REPLACE FUNCTION ObtenerCantidadProductoCita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER
            ) RETURN NUMBER IS
                v_cantidad NUMBER;
            BEGIN
                SELECT cantidad INTO v_cantidad
                FROM productos_usados_cita
                WHERE citaId = p_citaId 
                AND inventarioId = p_inventarioId;

                RETURN v_cantidad;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    RETURN 0;
                WHEN OTHERS THEN
                    RETURN -1;
            END;
        ");

        // Función para actualizar cantidad en inventario
        DB::unprepared("
            CREATE OR REPLACE FUNCTION ActualizarCantidadInventario(
                p_inventarioId IN NUMBER,
                p_diferencia   IN NUMBER
            ) RETURN NUMBER IS
            BEGIN
                UPDATE inventario
                SET cantidadUnidades = cantidadUnidades - p_diferencia
                WHERE id = p_inventarioId;

                RETURN 1;
            EXCEPTION
                WHEN OTHERS THEN
                    RETURN 0;
            END;
        ");

        // Función para actualizar cantidad en cita
        DB::unprepared("
            CREATE OR REPLACE FUNCTION ActualizarCantidadEnCita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_nuevaCantidad IN NUMBER
            ) RETURN NUMBER IS
            BEGIN
                IF p_nuevaCantidad > 0 THEN
                    UPDATE productos_usados_cita
                    SET cantidad = p_nuevaCantidad
                    WHERE citaId = p_citaId 
                    AND inventarioId = p_inventarioId;
                ELSE
                    DELETE FROM productos_usados_cita
                    WHERE citaId = p_citaId 
                    AND inventarioId = p_inventarioId;
                END IF;

                RETURN 1;
            EXCEPTION
                WHEN OTHERS THEN
                    RETURN 0;
            END;
        ");

        // Función para actualizar cantidad de un producto en una cita
        DB::unprepared("
            CREATE OR REPLACE FUNCTION ActualizarCantidadProducto(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_nuevaCantidad IN NUMBER
            ) RETURN NUMBER IS
                v_cantidadActual NUMBER;
                v_diferencia     NUMBER;
                v_resultado      NUMBER;
            BEGIN
                -- Obtener cantidad actual
                v_cantidadActual := ObtenerCantidadProductoCita(p_citaId, p_inventarioId);
                IF v_cantidadActual = -1 THEN
                    RETURN 0;
                END IF;

                -- Calcular diferencia
                v_diferencia := p_nuevaCantidad - v_cantidadActual;

                -- Actualizar inventario
                v_resultado := ActualizarCantidadInventario(p_inventarioId, v_diferencia);
                IF v_resultado = 0 THEN
                    RETURN 0;
                END IF;

                -- Actualizar o eliminar de la cita
                v_resultado := ActualizarCantidadEnCita(p_citaId, p_inventarioId, p_nuevaCantidad);
                IF v_resultado = 0 THEN
                    RETURN 0;
                END IF;

                RETURN 1;
            END;
        ");

        // SP: actualizar cantidad de un producto en una cita
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ActualizarCantidadProductoCita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_nuevaCantidad IN NUMBER,
                p_exito        OUT NUMBER
            ) IS
            BEGIN
                p_exito := ActualizarCantidadProducto(p_citaId, p_inventarioId, p_nuevaCantidad);
                COMMIT;
            END;
        ");

        // Función para obtener cantidad de un producto en una cita
        DB::unprepared("
            CREATE OR REPLACE FUNCTION ObtenerCantidadProducto(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER
            ) RETURN NUMBER IS
                v_cantidad NUMBER;
            BEGIN
                SELECT cantidad INTO v_cantidad
                FROM productos_usados_cita
                WHERE citaId = p_citaId 
                AND inventarioId = p_inventarioId;

                RETURN v_cantidad;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    RETURN 0;
                WHEN OTHERS THEN
                    RETURN -1;
            END;
        ");

        // Función para restaurar inventario
        DB::unprepared("
            CREATE OR REPLACE FUNCTION RestaurarInventario(
                p_inventarioId IN NUMBER,
                p_cantidad     IN NUMBER
            ) RETURN NUMBER IS
            BEGIN
                UPDATE inventario
                SET cantidadUnidades = cantidadUnidades + p_cantidad
                WHERE id = p_inventarioId;

                RETURN 1;
            EXCEPTION
                WHEN OTHERS THEN
                    RETURN 0;
            END;
        ");

        // Función para eliminar un producto específico de una cita
        DB::unprepared("
            CREATE OR REPLACE FUNCTION EliminarProducto(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER
            ) RETURN NUMBER IS
                v_cantidad NUMBER;
                v_resultado NUMBER;
            BEGIN
                -- Obtener cantidad actual
                v_cantidad := ObtenerCantidadProducto(p_citaId, p_inventarioId);
                IF v_cantidad = -1 THEN
                    RETURN 0;
                END IF;

                -- Actualizar inventario
                v_resultado := RestaurarInventario(p_inventarioId, v_cantidad);
                IF v_resultado = 0 THEN
                    RETURN 0;
                END IF;

                -- Eliminar de la cita
                DELETE FROM productos_usados_cita
                WHERE citaId = p_citaId 
                AND inventarioId = p_inventarioId;

                RETURN 1;
            END;
        ");

        // SP: eliminar un producto específico de una cita
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE EliminarProductoDeCita(
                p_citaId       IN NUMBER,
                p_inventarioId IN NUMBER,
                p_exito        OUT NUMBER
            ) IS
            BEGIN
                p_exito := EliminarProducto(p_citaId, p_inventarioId);
                COMMIT;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE ConsultarProductosUsadosCitaPorId");
        DB::unprepared("DROP PROCEDURE InsertarProductoUsadoCita");
        DB::unprepared("DROP PROCEDURE EliminarProductosUsadosCitaPorId");
        DB::unprepared("DROP PROCEDURE ActualizarCantidadProductoCita");
        DB::unprepared("DROP PROCEDURE EliminarProductoDeCita");
        DB::unprepared("DROP FUNCTION DescontarUnidadesProducto");
        DB::unprepared("DROP FUNCTION AgregarProductoACita");
        DB::unprepared("DROP FUNCTION ObtenerCantidadProductoCita");
        DB::unprepared("DROP FUNCTION ActualizarCantidadInventario");
        DB::unprepared("DROP FUNCTION ActualizarCantidadEnCita");
        DB::unprepared("DROP FUNCTION ActualizarCantidadProducto");
        DB::unprepared("DROP FUNCTION ObtenerCantidadProducto");
        DB::unprepared("DROP FUNCTION RestaurarInventario");
        DB::unprepared("DROP FUNCTION EliminarProducto");
        DB::unprepared("DROP TABLE productos_usados_cita");
    }
};