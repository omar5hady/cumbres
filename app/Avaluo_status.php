<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avaluo_status extends Model
{
    protected $table = 'avaluo_status'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['avaluo_id','status','observacion'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function avaluo(){
        return $this->belongsTo('App\Avaluo');
    }
}
