<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'creditos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['prospecto_id','num_dep_economicos','tipo_economia','nombre_primera_ref',
                            'telefono_primera_ref','celular_primera_ref',
                            'nombre_segunda_ref','telefono_segunda_ref','celular_segunda_ref',
                            'etapa','manzana','num_lote','modelo','precio_base',
                            'superficie','terreno_excedente','precio_terreno_excedente',
                            'promocion','descripcion_promocion','descuento_promocion',
                            'paquete','descripcion_paquete','costo_paquete','precio_venta','plazo',
                            'credito_solic','status','lote_id','precio_obra_extra',
                            'costo_protecciones', 'costo_cuota_mant', 'costo_alarma',
                            'descuento_terreno', 'costo_descuento',
                            'contrato','fraccionamiento','bono'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    


    public function prospecto(){
        return $this->belongsTo('App/Cliente');
    }

    public function lote(){
        return $this->belongsTo('App/Lote');
    }

    public function vendedor(){
        return $this->belongsTo('App/Vendedor');
    }


    public function dato_extra(){
        return $this->hasOne('App/Dato_extra');
    }

    public function contrato(){
        return $this->hasOne('App/Contrato');
    }

    public function inst_seleccionadas(){
        return $this->hasMany('App/inst_seleccionada');
    }
}
