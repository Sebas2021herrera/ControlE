<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especifica la tabla asociada con el modelo
    protected $table = 'usuarios';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'correo_personal',
        'correo_institucional',
        'telefono',
        'roles_id',
        'numero_ficha',
        'contraseña', // Cambiado de 'password' a 'contraseña'
        'foto',
    ];

    // Campos que deben ser ocultos en los arrays de respuesta
    protected $hidden = [
        'contraseña', // Cambiado de 'password' a 'contraseña'
        'remember_token',
    ];

    // Método para obtener la contraseña
    public function getAuthPassword()
    {
        return $this->contraseña; // Cambiado de 'password' a 'contraseña'
    }

    // Relación con el modelo Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    // Relación con el modelo Elemento
    public function elementos()
    {
        return $this->hasMany(Elemento::class, 'usuario_id');
    }

    // Relación con ControlIngreso
    public function controlIngresos()
    {
        return $this->hasMany(ControlIngreso::class, 'usuario_id');
    }

    // Accesor para obtener la URL de la foto del perfil
    public function getFotoPerfilAttribute()
    {
        return $this->foto ? asset('storage/fotos_perfil/' . $this->foto) : asset('imagenes/sin_foto_perfil.webp');
    }
}
