<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'record_id',
        'nombre',
        'telefono1',
        'telefono2',
        'email',
        'direccion',
        'parentesco'
    ];
    public $timestamps = false;

}
