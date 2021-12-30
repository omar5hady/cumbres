<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial_plano extends Model
{
    protected $table = 'historial_planos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [ 
                    'fraccionamiento_id',
                    'archivo',
                    'version'
                ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }
}
