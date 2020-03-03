<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version_modelo extends Model
{
    protected $table = 'versiones_modelos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['modelo_id','archivo','version'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function modelo(){
        return $this->belongsTo('App\Modelo');
    }

}
