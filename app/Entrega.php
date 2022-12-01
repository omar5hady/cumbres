<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fecha_program','hora_entrega_prog',
                            'status','fecha_entrega_real','hora_entrega_real',
                            'revision_previa', 'puntualidad', 'cero_detalles',
                            'entrega_file', 'garantia_file',
                            'cont_reprogram'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->hasOne('App\Contrato');
    }

    public function obs_entrega(){
        return $this->hasMany('App\Obs_entrega');
    }

}
