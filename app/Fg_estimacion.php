<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fg_estimacion extends Model
{
    protected $table = 'fg_estimaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'aviso_id',
        'cantidad',
        'monto_fg',
        'fecha_fg',
    ];


    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }
}
