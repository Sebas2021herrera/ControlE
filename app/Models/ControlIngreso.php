<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlIngreso extends Model
{
    use HasFactory;

    // Especifica la tabla asociada con el modelo
    protected $table = 'control_ingresos';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'usuario_id', // Relacionado con el modelo Usuario
        'centros_id', // Relacionado con el modelo Centro
        'fecha_ingreso',
        'estado',
        'id_persona_control', // Número de documento del vigilante
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
            'estado' => $this->estado,
            'id_persona_control' => $this->id_persona_control,
        ];
    }

    public function elementos()
    {
        return $this->hasMany(Sub_Control_Ingreso::class, 'control_ingreso_id');
    }
}
