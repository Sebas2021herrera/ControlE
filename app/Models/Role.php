<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención de nombres pluralizados)
    protected $table = 'roles';

    // Campos asignables en masa
    protected $fillable = [
        'nombre',
        
    ];

    // Relación con el modelo Usuario
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'roles_id');
    }
}
