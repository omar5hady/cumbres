<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    protected $table = 'fraccionamientos'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['nombre','tipo_proyecto','calle','colonia','estado','ciudad','fecha_ini_venta',
                            'archivo_planos','archivo_escrituras','delegacion','cp','email_administracion',
                            'logo_fracc', 'numero', 'gerente_id','arquitecto_id','postventa_id'];//asignacion en masa, definir las columnas de la tabla a la que se les mandaran valores

    public function etapa(){
        return $this->hasMany('App\Etapa');
    }

    public function modelo(){
        return $this->hasMany('App\Modelo');
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

    public function cliente(){
        return $this->hasMany('App\Cliente');
    }

    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }
}
