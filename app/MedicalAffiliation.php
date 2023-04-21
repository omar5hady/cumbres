<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalAffiliation extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'record_id',
        'proveedor',
        'poliza',
        'tipo',

    ];

}
