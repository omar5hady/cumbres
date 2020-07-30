<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimacion extends Model
{
    protected $table = 'estimaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'aviso_id','partida','pu_prorrateado','cant_tope',
    ];


    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }
}
