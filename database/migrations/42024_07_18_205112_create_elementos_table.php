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
        Schema::create('elementos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id'); // O el nombre correcto de la columna
            // Si hay una relación con una tabla de categorías
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->string('descripcion');
            $table->string('marca');
            $table->string('modelo');
            $table->string('serie');
            $table->text('especificaciones_tecnicas')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elementos');
    }
};
