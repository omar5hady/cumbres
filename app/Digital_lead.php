<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Digital_lead extends Model
{
    protected $table = 'digital_leads'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'nombre',
        'apellidos',
        'celular',
        'nss',
        'curp',
        'rfc',
        'f_nacimiento',
        'email',
        'sexo',
        'asignación',
        'proyecto'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function obs_leads(){
        return $this->belongsTo('App\Obs_lead');
    }
}
