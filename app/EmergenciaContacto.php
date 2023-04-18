<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmergenciaContacto extends Model
{
    protected $fillable = [
        'nombre','telefono','telefono_2','direccion','correo',
        'user_id','parentesco'
    ];

    public function persona(){
        return $this->belongsTo('App\Personal','user_id');
    }
}
