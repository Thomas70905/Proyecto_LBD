<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Crea la tabla administradores (solo foreign key a usuarios)
        DB::unprepared("
            CREATE TABLE administradores (
                id         NUMBER GENERATED BY DEFAULT ON NULL AS IDENTITY PRIMARY KEY,
                idUsuario  NUMBER          NOT NULL,
                CONSTRAINT fk_administradores_usuarios FOREIGN KEY (idUsuario) REFERENCES usuarios(id)
            )
        ");

        // Procedimiento para consultar todos los administradores
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarAdministradores(cur OUT SYS_REFCURSOR) IS
            BEGIN
                OPEN cur FOR
                SELECT
                  a.id,
                  u.id              AS idUsuario,
                  u.email         AS correo,
                  u.rol,
                  u.nombre_completo,
                  u.telefono,
                  u.en_recuperacion
                FROM administradores a
                JOIN usuarios u ON a.idUsuario = u.id;
            END;
        ");

        // Procedimiento para consultar un administrador por id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ConsultarAdministradorPorId(
                p_id IN NUMBER,
                cur  OUT SYS_REFCURSOR
            ) IS
            BEGIN
                OPEN cur FOR
                SELECT
                  a.id,
                  u.id              AS idUsuario,
                  u.email         AS correo,
                  u.rol,
                  u.nombre_completo,
                  u.telefono,
                  u.en_recuperacion
                FROM administradores a
                JOIN usuarios u ON a.idUsuario = u.id
               WHERE a.id = p_id;
            END;
        ");

        // Procedimiento para actualizar un administrador
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE ActualizarAdministrador(
                p_id        IN NUMBER,
                p_idUsuario IN NUMBER
            ) IS
            BEGIN
                UPDATE administradores
                   SET idUsuario = p_idUsuario
                 WHERE id = p_id;
                COMMIT;
            END;
        ");

        // Procedimiento para eliminar un administrador por id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE EliminarAdministradorPorId(p_id IN NUMBER) IS
            BEGIN
                DELETE FROM administradores WHERE id = p_id;
                COMMIT;
            END;
        ");

        // Procedimiento para insertar un administrador y retornar su id
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE InsertarAdministrador(
                p_idUsuario IN NUMBER,
                p_id        OUT NUMBER
            ) IS
            BEGIN
                INSERT INTO administradores (idUsuario)
                VALUES (p_idUsuario)
                RETURNING id INTO p_id;
                COMMIT;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE ConsultarAdministradores");
        DB::unprepared("DROP PROCEDURE ConsultarAdministradorPorId");
        DB::unprepared("DROP PROCEDURE ActualizarAdministrador");
        DB::unprepared("DROP PROCEDURE EliminarAdministradorPorId");
        DB::unprepared("DROP PROCEDURE InsertarAdministrador");
        DB::unprepared("DROP TABLE administradores");
    }
};