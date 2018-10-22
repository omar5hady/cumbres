<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sobreprecio_etapa extends Model
{
    protected $table = 'sobreprecios_etapas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['etapa_id','sobreprecio_id','sobreprecio'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    

    public function sobreprecio_etapa(){
        return $this->hasMany('App\Etapa');
    }

    public function sobreprecio(){
        return $this->hasMany('App\Sobreprecio');
    }

    public function sobreprecio_etapa_modelo(){
        return $this->belongsTo('App\Sobreprecio_modelo');
    }
}
