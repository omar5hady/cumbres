<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiaFestivo extends Model
{
    protected $table = 'dia_festivos';
    protected $fillable = [
        'id', 'fecha', 'medio_dia', 'nombre'
    ];

}
