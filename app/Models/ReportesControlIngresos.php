<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlIngreso;
use App\Models\Elemento;

class ReportesControlIngresos extends Model
{
    use HasFactory;

    // Especifica la tabla asociada con el modelo
    protected $table = 'sub_control_ingresos';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'control_ingreso_id',
        'elemento_id',
    ];

    // Relación con el modelo ControlIngreso
    public function controlIngreso()
    {
        return $this->belongsTo(ControlIngreso::class, 'control_ingresos_id', 'id');
    }

    // Relación con el modelo Elemento
    public function elemento()
    {
        return $this->belongsTo(Elemento::class, 'elemento_id', 'id');
    }
}