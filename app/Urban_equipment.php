<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Urban_equipment extends Model
{
    protected $fillable = [
        'fraccionamiento_id', 'categoria', 'nombre','descripcion'
    ];

    public function fraccionamiento(){
        return $this->belongsTo('App\Fraccionamiento','fraccionamiento_id');
    }
}

