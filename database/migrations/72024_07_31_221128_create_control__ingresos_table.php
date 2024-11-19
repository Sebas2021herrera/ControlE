<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centros_id')->constrained('centros')->onDelete('cascade'); // Relación con centros
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Relación con usuarios
            $table->string('id_persona_control', 50); // Documento del vigilante
            $table->timestamp('fecha_ingreso')->nullable(); // Fecha y hora de ingreso
            $table->timestamp('fecha_salida')->nullable(); // Fecha y hora de salida
            $table->tinyInteger('estado')->default(0); // 0 = abierto, 1 = cerrado
            $table->timestamps();

            // Índices
            $table->index(['usuario_id', 'estado']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_ingresos');
    }
}
