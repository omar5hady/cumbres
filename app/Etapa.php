<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $table = 'etapas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','num_etapa','f_ini',
        'f_fin','personal_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function personal(){
        return $this->belongsTo('App\Personal');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }
}
