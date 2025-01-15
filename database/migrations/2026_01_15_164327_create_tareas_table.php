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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id(); // Identificador único de la tarea
            $table->string('tarea'); // Nombre de la tarea
            $table->text('descripcion'); // Descripción de la tarea
            $table->date('fecha'); // Fecha asociada a la tarea
            $table->foreignId('idlugar')->constrained('lugares')->onDelete('cascade'); // Relación con lugares
            $table->foreignId('idmomento')->constrained('momentodia')->onDelete('cascade'); // Relación con momentodia
            $table->foreignId('idtipo')->constrained('tipos')->onDelete('cascade'); // Relación con tipos
            $table->foreignId('idestado')->constrained('estados')->onDelete('cascade'); // Relación con estados
            $table->unsignedInteger('likes')->default(0); // Número de likes
            $table->unsignedInteger('dislikes')->default(0); // Número de dislikes
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
