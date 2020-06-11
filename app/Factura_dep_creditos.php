<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura_dep_creditos extends Model
{
    public function dep_creditos(){
        return $this->belongsTo('App\Dep_credito');
    }
}
