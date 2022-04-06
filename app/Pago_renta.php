<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago_renta extends Model
{
    protected $table = 'pagos_rentas'; // se referencia a que tabla pertenece el modelo
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = ['renta_id','num_pago','importe','fecha'];


    public function contrato(){
        return $this->belongsTo('App\Contrato');
    }
}
