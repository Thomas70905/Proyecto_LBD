<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migración CreateUsuariosTable
 *
 * Esta migración crea la tabla 'usuarios' con las siguientes columnas:
 * - id: NUMBER generado como identidad (PRIMARY KEY)
 * - email: VARCHAR2(255) NOT NULL UNIQUE
 * - password: VARCHAR2(255) NOT NULL
 * - rol: VARCHAR2(50) NOT NULL
 * - nombre_completo: VARCHAR2(255) NOT NULL
 * - telefono: VARCHAR2(50) NULL
 * - en_recuperacion: NUMBER(1) NOT NULL DEFAULT 0
 *
 * Además, define los procedimientos almacenados:
 * - ConsultarUsuarios: devuelve todos los usuarios
 * - ConsultarUsuarioPorId: devuelve un usuario por ID
 * - ConsultarUsuarioPorCorreo: devuelve un usuario por correo
 * - ConsultarEmpleados: devuelve usuarios con rol veterinario o administrador
 * - ActualizarUsuario: actualiza datos de un usuario
 * - EliminarUsuarioPorId: elimina un usuario por ID
 * - InsertarUsuario: inserta un nuevo usuario y retorna su ID
 * - MarcarUsuarioRecuperacionContrasenaPorCorreo: marca al usuario en recuperación y actualiza contraseña temporal
 */
return new class extends Migration {
    public function up(): void
    {
        // Crea la tabla usuarios
        DB::unprepared("
            CREATE TABLE usuarios (
                id                NUMBER GENERATED BY DEFAULT ON NULL AS IDENTITY PRIMARY KEY,
                email             VARCHAR2(255) NOT NULL UNIQUE,
                password          VARCHAR2(255) NOT NULL,
                rol               VARCHAR2(50)  NOT NULL,
                nombre_completo   VARCHAR2(255) NOT NULL,
                telefono          VARCHAR2(50),
                en_recuperacion   NUMBER(1)      DEFAULT 0    NOT NULL
            )
        ");

        // SP: consultar todos los usuarios
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarUsuarios(cur OUT SYS_REFCURSOR) IS
            BEGIN
                OPEN cur FOR
                SELECT id, email, password, rol, nombre_completo, telefono, en_recuperacion
                  FROM usuarios;
            END;
        ");

        // SP: consultar un usuario por id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarUsuarioPorId(
                p_id IN NUMBER,
                cur  OUT SYS_REFCURSOR
            ) IS
            BEGIN
                OPEN cur FOR
                SELECT id, email, password, rol, nombre_completo, telefono, en_recuperacion
                  FROM usuarios
                 WHERE id = p_id;
            END;
        ");

        // SP: consultar un usuario por correo
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarUsuarioPorCorreo(
                p_email IN VARCHAR2,
                cur      OUT SYS_REFCURSOR
            ) IS
            BEGIN
                OPEN cur FOR
                SELECT id, email, password, rol, nombre_completo, telefono, en_recuperacion
                  FROM usuarios
                 WHERE email = p_email;
            END;
        ");

        // SP: consultar empleados (rol = veterinario o administrador)
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarEmpleados(cur OUT SYS_REFCURSOR) IS
            BEGIN
                OPEN cur FOR
                SELECT id, email, password, rol, nombre_completo, telefono, en_recuperacion
                  FROM usuarios
                 WHERE rol IN ('veterinario','administrador');
            END;
        ");

        // SP: actualizar un usuario
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ActualizarUsuario(
                p_id               IN NUMBER,
                p_email            IN VARCHAR2,
                p_password         IN VARCHAR2,
                p_rol              IN VARCHAR2,
                p_nombre_completo  IN VARCHAR2,
                p_telefono         IN VARCHAR2,
                p_en_recuperacion  IN NUMBER
            ) IS
            BEGIN
                UPDATE usuarios
                   SET email            = p_email,
                       password         = p_password,
                       rol              = p_rol,
                       nombre_completo  = p_nombre_completo,
                       telefono         = p_telefono,
                       en_recuperacion  = p_en_recuperacion
                 WHERE id = p_id;
                COMMIT;
            END;
        ");

        // SP: eliminar un usuario por id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE EliminarUsuarioPorId(p_id IN NUMBER) IS
            BEGIN
                DELETE FROM usuarios WHERE id = p_id;
                COMMIT;
            END;
        ");

        // SP: insertar un usuario y retornar su id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE InsertarUsuario(
                p_email            IN VARCHAR2,
                p_password         IN VARCHAR2,
                p_rol              IN VARCHAR2,
                p_nombre_completo  IN VARCHAR2,
                p_telefono         IN VARCHAR2,
                p_en_recuperacion  IN NUMBER,
                p_id               OUT NUMBER
            ) IS
            BEGIN
                INSERT INTO usuarios (
                    email, password, rol,
                    nombre_completo, telefono, en_recuperacion
                ) VALUES (
                    p_email, p_password, p_rol,
                    p_nombre_completo, p_telefono, p_en_recuperacion
                )
                RETURNING id INTO p_id;
                COMMIT;
            END;
        ");

        // SP: marcar en_recuperacion = 1 y actualizar contraseña temporal
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE MarcarUsuarioRecuperacionContrasenaPorCorreo(
                p_email           IN VARCHAR2,
                p_password_temp   IN VARCHAR2
            ) IS
            BEGIN
                UPDATE usuarios
                   SET en_recuperacion = 1,
                       password        = p_password_temp
                 WHERE email = p_email;
                COMMIT;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE ConsultarUsuarios");
        DB::unprepared("DROP PROCEDURE ConsultarUsuarioPorId");
        DB::unprepared("DROP PROCEDURE ConsultarUsuarioPorCorreo");
        DB::unprepared("DROP PROCEDURE ConsultarEmpleados");
        DB::unprepared("DROP PROCEDURE ActualizarUsuario");
        DB::unprepared("DROP PROCEDURE EliminarUsuarioPorId");
        DB::unprepared("DROP PROCEDURE InsertarUsuario");
        DB::unprepared("DROP PROCEDURE MarcarUsuarioRecuperacionContrasenaPorCorreo");
        DB::unprepared("DROP TABLE usuarios");
    }
};