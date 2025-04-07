<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nombre', 255);
            $table->string('apellido', 255);
            $table->string('telefono', 50);
            $table->string('email', 150);
            $table->string('direccion', 255);
            $table->date('fecha_registro')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}