<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sobreprecio_modelo extends Model
{
    protected $table = 'sobreprecios_modelos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['id','lote_id','sobreprecio_etapa_id','ajuste'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    


    public function sobreprecio_modelo(){
        return $this->hasMany('App\Lote');
    }

    public function sobreprecio_etapa_modelo(){
        return $this->hasMany('App\Sobreprecio_etapa');
    }

}
