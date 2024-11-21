<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition()
    {
        return [
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'tipo_documento' => $this->faker->randomElement(['CC', 'TI', 'CE']),
            'numero_documento' => $this->faker->unique()->numerify('#########'),
            'rh' => $this->faker->randomElement(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']),
            'correo_personal' => $this->faker->unique()->safeEmail,
            'correo_institucional' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'roles_id' => $this->faker->numberBetween(1, 3), // El id del rol correspondiente
            'numero_ficha' => $this->faker->numerify('A#####'),
            'contraseña' => Hash::make('password'),
            'foto' => null,
        ];
    }
}
