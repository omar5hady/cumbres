<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cat_detalle_subconcepto extends Model
{
    protected $table = 'cat_detalles_subconceptos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'subconcepto','id_gral'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public $timestamps = false;

    public function cat_detalle(){
        return $this->hasMany('App\Cat_detalle');
    }

    public function cat_detalle_general(){
        return $this->belongsTo('App\Cat_detalle_general');
    }
}
