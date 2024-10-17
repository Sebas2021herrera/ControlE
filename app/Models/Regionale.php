<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regionale extends Model
{
    use HasFactory;

    public function centros()
    {
        return $this->hasMany(Centro::class, 'regionales_id');
    }
}
