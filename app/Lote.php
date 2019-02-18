<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lote extends Model
{
    protected $table = 'lotes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','manzana','num_lote','sublote','modelo_id',
                            'empresa_id','calle','numero','interior','terreno','construccion','casa_muestra',
                            'lote_comercial','ini_obra','clv_catastral','etapa_servicios','credito_puente',
                            'siembra','fecha_ini','fecha_fin','arquitecto_id','ehl_solicitado','sobreprecio',
                            'aviso','obra_extra', 'habilitado','apartado','excedente_terreno','precio_base'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores
    
                           
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

    public function licencia(){
        return $this->hasOne('App\Licencia');
    }

    public function personal(){
        return $this->belongsTo('App\Personal');
    }

    public function avance(){
        return $this->hasMany('App\Avance');
    }

    public function Apartado(){
        return $this->hasMany('App\Apartado');
    }

}
