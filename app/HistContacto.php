<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistContacto extends Model
{
    protected $fillable = [
        'prospecto_id',
        'lead_id',
        'telefono',
        'celular',
        'email',
        'usuario'
    ];
}
