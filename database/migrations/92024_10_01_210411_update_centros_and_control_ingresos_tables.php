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
        // Actualizar la tabla 'centros'
        Schema::table('centros', function (Blueprint $table) {
            $table->string('direccion')->after('nombre'); // Agregar la columna 'direccion'
        });

        // Actualizar la tabla 'control_ingresos'
        Schema::table('control_ingresos', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('usuarios')->after('observacion'); // Agregar columna para la relación con 'usuarios'
            $table->string('id_persona_control')->after('observacion'); // Agregar la columna 'id_persona_control'
            $table->string('estado')->after('id_persona_control'); // Agregar la columna 'estado'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir cambios en la tabla 'centros'
        Schema::table('centros', function (Blueprint $table) {
            $table->dropColumn('direccion');
        });

        // Revertir cambios en la tabla 'control_ingresos'
        Schema::table('control_ingresos', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropColumn(['usuario_id', 'id_persona_control', 'estado']);
        });
    }
};
