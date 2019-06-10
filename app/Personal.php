<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'departamento_id','apellidos','nombre','f_nacimiento','rfc','homoclave','direccion','colonia','cp'
        ,'telefono','ext','celular','email','activo'
        ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
    public function departamento(){
        return $this->belongsTo('App\Departamento');
    }

    public function empresa(){
        return $this->belongsTo('App\Empresa');
    }

    public function etapa(){
        return $this->hasMany('App\Etapa');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }

    public function expediente(){
        return $this->hasMany('App\Expediente');
    }

    public function user(){
        return $this->hasOne('App\User');
    }

    public function vendedor(){
        return $this->hasOne('App\Vendedor');
    }

    public function cliente(){
        return $this->hasOne('App\Vendedor');
    } 

}
