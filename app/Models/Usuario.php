<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

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
        'contraseña',
    ];

    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    protected $casts = [
        'correo_personal_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['contraseña'] = bcrypt($password);
    }

    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
