<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración CreateSessionsTable
 *
 * Esta migración crea la tabla 'sessions' para almacenar las sesiones de los usuarios,
 * con las siguientes columnas:
 * - id: identificador de sesión (PRIMARY KEY)
 * - user_id: referencia al usuario (FOREIGN KEY), nullable e indexado
 * - ip_address: dirección IP del cliente, nullable
 * - user_agent: información del agente de usuario, nullable
 * - payload: datos serializados de la sesión
 * - last_activity: timestamp de la última actividad, indexado
 *
 * Utiliza el constructor de esquema de Laravel (Blueprint).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
