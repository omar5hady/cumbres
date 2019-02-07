<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente_observacion extends Model
{
    protected $table = 'clientes_observaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['cliente_id','comentario','usuario'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function cliente(){
        return $this->belongsTo('App\Cliente');
    }
    
}
