<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres', 'apellidos', 'tipo_documento', 'numero_documento',
        'correo_personal', 'correo_institucional', 'telefono', 'roles_id',
        'numero_ficha', 'contraseña', 'foto'
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
        return $this->hasMany(Elemento::class, 'usuario_id');
    }

    // Método accesor para obtener la URL de la foto del perfil
    public function getFotoPerfilAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('imagenes/sin_foto_perfil.webp');
    }
}
