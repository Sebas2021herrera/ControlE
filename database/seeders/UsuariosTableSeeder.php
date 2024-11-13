<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombres' => 'Sebastian',
                'apellidos' => 'Herrera',
                'tipo_documento' => 'CC',
                'numero_documento' => '12345678',
                'rh' => 'O+',
                'correo_personal' => 'sebastianherrera@egmail.com',
                'correo_institucional' => 'sebastian.herrera@soy.sena.edu.co',
                'telefono' => '3001234567',
                'roles_id' => 1, // Admin role, assuming role with ID 1 is 'Admin'
                'numero_ficha' => 'A12345',
                'contraseña' => Hash::make('contraseña123'), // Encriptar la contraseña
                'foto' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombres' => 'Ana',
                'apellidos' => 'Gómez',
                'tipo_documento' => 'CC',
                'numero_documento' => '87654321',
                'rh' => 'A-',
                'correo_personal' => 'ana.gomez@example.com',
                'correo_institucional' => 'agomez@instituto.edu',
                'telefono' => '3107654321',
                'roles_id' => 2, // Control role, assuming role with ID 2 is 'Control'
                'numero_ficha' => 'B67890',
                'contraseña' => Hash::make('contraseña456'), // Encriptar la contraseña
                'foto' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombres' => 'Juan',
                'apellidos' => 'Perez',
                'tipo_documento' => 'CC',
                'numero_documento' => '87654321',
                'rh' => 'A-',
                'correo_personal' => 'juanperez@gmail.com',
                'correo_institucional' => 'juan.perez@soy.sena.edu.co',
                'telefono' => '3107654321',
                'roles_id' => 3, // Control role, assuming role with ID 3 is 'Prendiz, Funcionario o Visitante.'
                'numero_ficha' => 'B67890',
                'contraseña' => Hash::make('contraseña456'), // Encriptar la contraseña
                'foto' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Puedes añadir más usuarios si lo necesitas
        ]);
    }
}
