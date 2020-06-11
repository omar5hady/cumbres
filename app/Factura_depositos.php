<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura_depositos extends Model
{
    public function deposito(){
        return $this->belongsTo('App\Deposito');
    }
}
