<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cat_detalle extends Model
{
    protected $table = 'cat_detalles'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'detalle','id_sub','activo'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public $timestamps = false;

    public function cat_detalle_subconcepto(){
        return $this->belongsTo('App\Cat_detalle_subconcepto');
    }
}

