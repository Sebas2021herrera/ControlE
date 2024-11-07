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
            $table->bigInteger('centros_id')->unsigned(); // Relación con centros
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Relación con usuarios
            $table->string('id_persona_control', 50); // Documento del vigilante que realiza el control
            $table->timestamp('fecha_ingreso')->nullable();
            $table->timestamp('fecha_salida')->nullable();
            $table->string('estado'); // Estado del ingreso (activo, inactivo, etc.)
            $table->timestamps(); // created_at, updated_a
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
