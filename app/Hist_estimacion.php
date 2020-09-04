<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hist_estimacion extends Model
{
    protected $table = 'hist_estimaciones'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'estimacion_id',
        'num_estimacion',
        'vol',
        'costo',
        'total_estimacion'
    ];


    public function estimaciones(){
        return $this->belongsTo('App\Estimacion');
    }
}
