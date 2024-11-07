<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubControlIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_control_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_ingreso_id')->constrained('control_ingresos')->onDelete('cascade');
            $table->foreignId('elemento_id')->constrained('elementos')->onDelete('cascade');
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
        Schema::dropIfExists('sub_control_ingresos');
    }
}
