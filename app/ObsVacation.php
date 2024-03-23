<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObsVacation extends Model
{
    protected $fillable = [
        'usuario',
        'observacion',
        'vacacion_id'
    ];
}
