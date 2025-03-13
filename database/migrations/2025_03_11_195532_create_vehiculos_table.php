<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 10)->unique();
            $table->string('color', 50);
            $table->string('marca', 50);
            $table->enum('tipo_vehiculo', ['particular', 'publico']);
            $table->unsignedBigInteger('conductor_id')->nullable();
            $table->unsignedBigInteger('propietario_id')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('conductor_id')->references('id')->on('conductores')->onDelete('set null');
            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
