<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Control', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Aprendiz', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Visitante', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Funcionario', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
