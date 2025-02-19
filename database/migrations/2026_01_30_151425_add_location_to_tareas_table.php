<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->string('ubicacion')->nullable()->after('descripcion');
        });
    }


    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('ubicacion');
        });
    }
};

