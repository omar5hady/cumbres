<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precios_terreno extends Model
{
    public function etapa(){
        return $this->belongsTo('App\Etapa');
    }
}
