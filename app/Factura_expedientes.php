<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura_expedientes extends Model
{
    public function expediente(){
        return $this->belongsTo('App\Expediente');
    }
}
