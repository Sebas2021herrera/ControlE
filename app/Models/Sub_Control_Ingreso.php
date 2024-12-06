<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlIngreso;
use App\Models\Elemento;

class Sub_Control_Ingreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'control_ingreso_id',
        'elemento_id',
    ];

    protected $table = 'sub__control__ingresos'; 

    public function controlIngreso()
    {
        return $this->belongsTo(ControlIngreso::class);
    }

    public function elemento()
    {
        return $this->belongsTo(Elemento::class);
    }
}
