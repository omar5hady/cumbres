<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terreno extends Model
{
    protected $table = 'terrenos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','manzana_id','num_lote','empresa_id',
                           'calle','numero','interior','terreno'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
public function fraccionamiento(){
    return $this->belongsTo('App\Fraccionamiento');
}

public function etapa(){
    return $this->belongsTo('App\Etapa');
}
}
