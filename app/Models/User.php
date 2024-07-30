<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Utiliza los traits HasFactory y Notifiable

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres', 'apellidos', 'tipo_documento', 'numero_documento',
        'correo_personal', 'correo_institucional', 'telefono', 'roles_id',
        'numero_ficha', 'contraseña',
    ];

    protected $hidden = [
        'contraseña', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function elementos()
    {
        return $this->hasMany(Elemento::class); // Define una relación de uno a muchos con el modelo Elemento
    }
}
