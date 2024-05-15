<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->unsignedBigInteger('id_restaurante');
            $table->foreign('id_restaurante')->references('id')->on('restaurantes');
            $table->string('nombre_reserva', 100);
            $table->text('tipo_comida');
            $table->string('ciudad', 100);
            $table->integer('numero_personas');
            $table->string('numero_contacto', 20);
            $table->string('codigo_reserva', 20)->unique();
            $table->dateTime('fecha_reserva');
            $table->enum('estado_reserva', ['activo', 'finalizado'])->default('activo');
            $table->timestamps(); // Añade created_at y updated_at automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
