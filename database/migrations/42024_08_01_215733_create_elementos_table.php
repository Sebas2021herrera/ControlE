<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elementos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade'); // Foreign key referencing categorias
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Foreign key referencing usuarios
            $table->string('descripcion');
            $table->string('marca');
            $table->string('modelo');
            $table->string('serie')->nullable();
            $table->text('especificaciones_tecnicas')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elementos');
    }
}
