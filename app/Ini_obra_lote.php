<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ini_obra_lote extends Model
{
    protected $table = 'ini_obra_lotes'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['ini_obra_id','lote','manzana','modelo','construccion','costo_directo',
                            'costo_indirecto','importe','descripcion','lote_id','obra_extra'];


    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }



}
