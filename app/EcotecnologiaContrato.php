<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EcotecnologiaContrato extends Model
{
    protected $fillable = [
        'ecotecnologia', 'precio','credito_id'
    ];
}
