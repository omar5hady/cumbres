<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartado extends Model
{
   
    protected $table = 'apartados'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['lote_id','vendedor_id','cliente_id','tipo_credito','fecha_apartado'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public $timestamps = false;

    public function lote(){
        return $this->belongsTo('App\Lote');
    }

    public function vendedor(){
        return $this->belongsTo('App\Vendedor');
    }

    public function cliente(){
        return $this->belongsTo('App\Cliente');
    }
}
