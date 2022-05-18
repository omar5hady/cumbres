<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['id','proveedor', 'contacto', 'direccion', 'colonia',
    'telefono', 'email', 'email2', 'poliza', 'tipo'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
   
    public function equipamiento(){
        return $this->hasMany('App\Equipamiento');
    }
}
