<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ini_obra extends Model
{
    protected $table = 'ini_obras'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['fraccionamiento_id','etapa_id','contratista_id','f_ini','f_fin','clave',
                           'total_costo_directo','total_costo_idirecto','total_importe','anticipo','total_anticipo',
                           'iniciado'];

    public function fraccionamiento(){
        return $this->hasMany('App\Fraccionamiento');
    }

    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }

    public function contratista(){
        return $this->hasMany('App\Contratista');
    }

    public function ini_obra_lote(){
        return $this->hasMany('App\Ini_obra_lote');
    }

   
}
