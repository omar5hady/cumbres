<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anticipo_estimacion extends Model
{
    protected $table = 'anticipos_estimaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'aviso_id',
        'fecha_anticipo',
        'monto_anticipo',
    ];


    public function ini_obra(){
        return $this->belongsTo('App\Ini_obra');
    }
}
