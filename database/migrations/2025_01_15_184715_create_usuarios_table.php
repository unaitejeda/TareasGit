<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // Identificador único del usuario
            $table->string('nombre'); // Nombre del usuario
            $table->string('apellido'); // Apellido del usuario
            $table->enum('sexo', ['M', 'F', 'Otro']); // Sexo del usuario
            $table->string('mail')->unique(); // Correo electrónico único
            $table->string('password'); // Contraseña encriptada
            $table->unsignedInteger('cantidad_tareas')->default(0); // Cantidad de tareas creadas
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
