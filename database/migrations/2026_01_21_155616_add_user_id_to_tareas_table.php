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
    Schema::table('tareas', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->after('id'); // Añadimos el campo user_id
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Relacionamos con la tabla users
    });
}

public function down()
{
    Schema::table('tareas', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}

};
