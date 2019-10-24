<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recep_equipamiento extends Model
{
    protected $table = 'recep_equipamientos'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = ['id','fecha_revision', 
                            'resultado', 'supervisor', 'observacion'
                            ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function solic_equipamiento(){
        return $this->belongsTo('App\Solic_equipamiento');
    }

    public function closet_acabado(){
        return $this->hasOne('App/Closet_acabado');
    }

    public function closet_interior(){
        return $this->hasOne('App/Closet_interior');
    }

    public function closet_otro(){
        return $this->hasOne('App/Closet_otro');
    }

    public function cocina_acabado(){
        return $this->hasOne('App/Cocina_acabado');
    }

    public function cocina_otra(){
        return $this->hasOne('App/Cocina_otra');
    }

    public function cocina_puerta(){
        return $this->hasOne('App/Cocina_puerta');
    }
}
