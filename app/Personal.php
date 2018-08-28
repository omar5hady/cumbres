<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'departamento_id','apellidos','nombre','f_nacimiento','rfc','direccion','colonia','cp'
        ,'telefono','ext','celular','email','activo'
        ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function departamento(){
        return $this->belongsTo('App\Departamento');
    }

    public function empresa(){
        return $this->belongsTo('App\Empresa');
    }
}
