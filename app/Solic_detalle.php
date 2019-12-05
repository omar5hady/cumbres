<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solic_detalle extends Model
{
    protected $table = 'solic_detalles'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
                        'contrato_id', 'contratista_id', 'cliente', 'dias_entrega', 'lunes', 'martes',
                        'miercoles', 'jueves', 'viernes', 'sabado', 'horario',
                        'celular', 'costo', 'status', 'hora_program', 'fecha_program'
    ];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }

    public function contratista(){
        return $this->belongsTo('App\Contratista');
    }

    public function descripcion_detalle(){
        return $this->hasMany('App\Descripcion_detalle');
    }
}
