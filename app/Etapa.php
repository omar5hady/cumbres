<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $table = 'etapas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','num_etapa','f_ini','fecha_ini_venta', 'terreno_m2',
        'f_fin','personal_id','costo_mantenimiento','plantilla_telecom','empresas_telecom','empresas_telecom_satelital','factibilidad',
        'num_cuenta_admin','clabe_admin','sucursal_admin','titular_admin','banco_admin','carta_bienvenida'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento');
    }

    public function personal(){
        return $this->belongsTo('App\Personal');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }

    public function terreno(){
        return $this->hasMany('App\Terreno');
    }

    public function precio_etapa(){
        return $this->hasMany('App\Precio_etapa');
    }

    public function sobreprecio_etapa(){
        return $this->hasMany('App\Sobreprecio_etapa');
    }

    public function promocion(){
        return $this->hasMany('App\Promocion');
    }

    public function serv_etapa(){
        return $this->hasMany('App\Serv_etapa');
    }

    public function precios_terreno(){
        return $this->hasMany('App\Precios_terreno')->orderBy('created_at', 'desc');
    }

    public function amenidad(){
        return $this->hasMany('App\Amenitie');
    }
}
