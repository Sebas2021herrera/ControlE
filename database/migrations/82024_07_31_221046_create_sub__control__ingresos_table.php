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
        Schema::create('sub__control__ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_ingresos_id')->constrained('control_ingresos');
            $table->foreignId('elementos_id')->constrained('elementos');
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub__control__ingresos');
    }
};
