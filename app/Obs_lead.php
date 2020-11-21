<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obs_lead extends Model
{
    protected $table = 'obs_leads'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'lead_id',
        'comentario',
        'usuario',
        'visto'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function lead(){
        return $this->belongsTo('App\Digital_lead');
    }
}
