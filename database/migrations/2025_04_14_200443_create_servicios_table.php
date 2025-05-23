<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migración CreateServiciosTable
 *
 * Esta migración crea la tabla 'servicios' con las siguientes columnas:
 * - id: NUMBER generado como identidad (PRIMARY KEY)
 * - nombre: VARCHAR2(255) NOT NULL
 * - descripcion: CLOB NOT NULL
 * - precio: NUMBER(10,2) NOT NULL
 * - duracion: NUMBER NOT NULL
 *
 * Además, define los procedimientos almacenados:
 * - ConsultarServicios: devuelve todos los servicios
 * - ConsultarServicioPorId: devuelve un servicio por su ID
 * - ActualizarServicio: actualiza los datos de un servicio
 * - EliminarServicioPorId: elimina un servicio por su ID
 * - InsertarServicio: inserta un nuevo servicio y retorna su ID
 */
return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
            CREATE TABLE servicios (
                id NUMBER GENERATED BY DEFAULT ON NULL AS IDENTITY PRIMARY KEY,
                nombre VARCHAR2(255) NOT NULL,
                descripcion CLOB NOT NULL,
                precio NUMBER(10,2) NOT NULL,
                duracion NUMBER NOT NULL
            )
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarServicios(cur OUT SYS_REFCURSOR) IS
            BEGIN
                OPEN cur FOR SELECT * FROM servicios;
            END;
        ");
        
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarServicioPorId(p_id IN NUMBER, cur OUT SYS_REFCURSOR) IS
            BEGIN
                OPEN cur FOR SELECT * FROM servicios WHERE id = p_id;
            END;
        ");
        
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ActualizarServicio(
                p_id IN NUMBER,
                p_nombre IN VARCHAR2,
                p_descripcion IN CLOB,
                p_precio IN NUMBER,
                p_duracion IN NUMBER
            ) IS
            BEGIN
                UPDATE servicios
                SET nombre = p_nombre,
                    descripcion = p_descripcion,
                    precio = p_precio,
                    duracion = p_duracion
                WHERE id = p_id;
                COMMIT;
            END;
        ");
        
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE EliminarServicioPorId(p_id IN NUMBER) IS
            BEGIN
                DELETE FROM servicios WHERE id = p_id;
                COMMIT;
            END;
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE InsertarServicio(
                p_nombre IN VARCHAR2,
                p_descripcion IN CLOB,
                p_precio IN NUMBER,
                p_duracion IN NUMBER,
                p_id OUT NUMBER
            ) IS
            BEGIN
                INSERT INTO servicios (nombre, descripcion, precio, duracion)
                VALUES (p_nombre, p_descripcion, p_precio, p_duracion)
                RETURNING id INTO p_id;
                COMMIT;
            END;
        ");
    }
    
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE ConsultarServicios");
        DB::unprepared("DROP PROCEDURE ConsultarServicioPorId");
        DB::unprepared("DROP PROCEDURE ActualizarServicio");
        DB::unprepared("DROP PROCEDURE EliminarServicioPorId");
        DB::unprepared("DROP PROCEDURE InsertarServicio");
    
        DB::unprepared("DROP TABLE servicios");
    }
};