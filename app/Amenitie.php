<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenitie extends Model
{
    protected $fillable = [
        'etapa_id', 'amenidad'
    ];

    public function etapa(){
        return $this->belongsTo('App\Etapa','etapa_id');
    }
}
