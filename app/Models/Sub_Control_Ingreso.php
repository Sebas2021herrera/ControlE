<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlIngreso;
use App\Models\Elemento;

class Sub_Control_Ingreso extends Model
{
    use HasFactory;

    // Actualiza las columnas a los nombres correctos
    protected $fillable = [
        'control_ingreso_id',  // Nombre correcto de la columna en la BD
        'elemento_id',
        'descripcion'
    ];

    protected $table = 'sub_control_ingresos'; // Asegúrate que esta tabla exista en la BD

    // Relación con ControlIngreso
    public function controlIngreso()
    {
        return $this->belongsTo(ControlIngreso::class, 'control_ingreso_id', 'id');
    }

    // Relación con Elemento
    public function elemento()
    {
        return $this->belongsTo(Elemento::class, 'elemento_id', 'id');
    }
}
