<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlIngreso; 

class Centro extends Model
{
    use HasFactory;

    // Define los campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',    
        'direccion', 
        'regionales_id' 
    ];

    // Define las relaciones si es necesario
    public function controlIngresos()
    {
        return $this->hasMany(ControlIngreso::class);
    }
}
