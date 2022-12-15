<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpObservacion extends Model
{
    protected $fillable = [
        'solicitud_id',
        'comentario',
        'usuario'
    ];
}
