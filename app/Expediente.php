<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $table = 'expedientes'; // se referencia a que tabla pertenece el modelo
    
    protected $fillable = ['id','fecha_integracion','gestor_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function Contrato()
    {
        return $this->belongsTo('App\Contrato');
    }

    public function Gestor()
    {
        return $this->belongsTo('App\Personal');
    }
}
