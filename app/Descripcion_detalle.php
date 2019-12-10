<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descripcion_detalle extends Model
{
    protected $table = 'descripcion_detalles'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
                        'solicitud_id', 'detalle_id', 'garantia', 'observacion', 'fecha_concluido',
                        'detalle', 'subconcepto', 'general', 'costo', 'revisado', 'resultado'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function solic_detalle(){
        return $this->belongsTo('App\Solic_detalle');
    }

    public function cat_detalle(){
        return $this->belongsTo('App\Cat_detalle');
    }
}
