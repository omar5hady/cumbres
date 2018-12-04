<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lote extends Model
{
    protected $table = 'lotes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','manzana','num_lote','sublote','modelo_id','empresa_id','calle','numero','interior','terreno',
                           'construccion','casa_muestra','lote_comercial'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }

    public function modelo(){
        return $this->belongsTo('App\Modelo');
    }

    public function empresa(){
        return $this->belongsTo('App\Empresa');
    }

    public function sobreprecio_modelo(){
        return $this->hasMany('App\Sobreprecio_modelo');
    }

    public function lote_promocion(){
        return $this->hasMany('App\Lote_promocion');
    }

    public function ini_obra_lote(){
        return $this->belongsTo('App\Ini_obra_lote');
    }

}
