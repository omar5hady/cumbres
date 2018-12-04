<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ini_obra_lote extends Model
{
    protected $table = 'ini_obra_lotes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['ini_obra_id','lote_id','manzana','superficie','costo_directo','costo_indirecto','importe',
                           'descripcion','iniciado'];


    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }

    public function lote(){
        return $this->hasMany('App\Lote');
    }


}
