<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id(); 
            $table->string('tarea');
            $table->text('descripcion');
            $table->date('fecha'); 
            $table->foreignId('idlugar')->constrained('lugares')->onDelete('cascade'); 
            $table->foreignId('idmomento')->constrained('momentodia')->onDelete('cascade');
            $table->foreignId('idtipo')->constrained('tipos')->onDelete('cascade'); 
            $table->foreignId('idestado')->constrained('estados')->onDelete('cascade');
            $table->unsignedInteger('likes')->default(0); 
            $table->unsignedInteger('dislikes')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
