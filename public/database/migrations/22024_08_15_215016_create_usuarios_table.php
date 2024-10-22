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
            $table->string('rh', 7);
            $table->string('correo_personal', 100)->unique();
            $table->string('correo_institucional', 100)->unique();
            $table->string('telefono', 20);
            $table->foreignId('roles_id')->constrained('roles');
            $table->string('numero_ficha', 50)->nullable();
            $table->string('contraseña', 255);
            $table->string('foto')->nullable(); 
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('usuarios');
    }
};
