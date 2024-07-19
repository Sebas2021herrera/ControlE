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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('tipo_documento', 50);
            $table->string('numero_documento', 50)->unique();
            $table->string('correo_personal', 100)->unique();
            $table->string('correo_institucional', 100)->unique();
            $table->string('telefono', 20);
            $table->foreignId('roles_id')->constrained('roles');//guarda id de el tipo al que pertence el rol
            $table->string('numero_ficha', 50);
            $table->string('contraseña', 255);
            $table->timestamps();
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
