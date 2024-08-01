<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemento extends Model
{
    use HasFactory; // Utiliza el trait HasFactory para las factorías de Eloquent.

    protected $fillable = [
        'categoria_id', 'usuario_id', 'descripcion', 'marca', 'modelo', 'serie', 'especificaciones_tecnicas', 'foto'
    ]; // Define los campos que pueden ser asignados masivamente.

    public function user()
    {
        return $this->belongsTo(User::class); // Define una relación de pertenencia con el modelo User.
    }
};