<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'categorias';

    // Campos que se pueden llenar mediante asignación masiva
    protected $fillable = ['nombre'];
}
