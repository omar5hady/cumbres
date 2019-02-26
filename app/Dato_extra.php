<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato_extra extends Model
{
    protected $table = 'datos_extra';
    protected $fillable = [
        'id', 'mascota', 'num_perros', 'rang010', 'rang1120','rang21','ama_casa',
        'persona_discap','silla_ruedas','num_vehiculos'
    ];
 
    public $timestamps = false;
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }
}
