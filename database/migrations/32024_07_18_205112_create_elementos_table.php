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
        Schema::create('elementos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias');//guarda id de el tipo al que pertence la categoria
            $table->string('descripcion');
            $table->string('marca', 20);
            $table->string('modelo', 20);
            $table->string('numero de serie o identificador del fabricante',100)->unique();
            $table->string('especificaciones tecnicas')->nullable();
            $table->string('foto_url')->nullable();
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
