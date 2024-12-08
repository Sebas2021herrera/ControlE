<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Centro;
use App\Models\Elemento;
use App\Models\Sub_Control_Ingreso;

class ControlIngreso extends Model
{
    use HasFactory;

    // Especifica la tabla asociada con el modelo
    protected $table = 'control_ingresos';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'usuario_id',
        'centros_id',
        'fecha_ingreso',
        'fecha_salida', // Agregar el campo fecha_salida
        'estado',
        'id_persona_control',
        'observacion',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Relación con el modelo Centro
    public function centro()
    {
        return $this->belongsTo(Centro::class, 'centros_id');
    }

    // Método para obtener información de ingreso
    public function getIngresoInfo()
    {
        return [
            'usuario' => $this->usuario, // Devuelve el usuario relacionado
            'centro' => $this->centro,    
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_salida' => $this->fecha_salida,
            'estado' => $this->estado,
            'id_persona_control' => $this->id_persona_control,
        ];
    }

    public function elementos()
    {
        return $this->hasMany(Elemento::class);
    }

    public function subControlIngresos()
    {
        return $this->hasMany(Sub_Control_Ingreso::class);
    }
}