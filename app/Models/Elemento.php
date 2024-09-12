<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Elemento extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'usuario_id',
        'descripcion',
        'marca',
        'modelo',
        'serie',
        'especificaciones_tecnicas',
        'foto'
    ];

    // Define las relaciones
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Accesor para obtener la URL de la foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : asset('imagenes/sin_imagen.jpg');
    }
}
