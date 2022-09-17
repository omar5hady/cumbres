<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $table = 'paquetes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','nombre',
                            'v_ini','v_fin','costo','descripcion','desc_equipamiento'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores


    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }
}
