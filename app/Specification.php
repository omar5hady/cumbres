<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    protected $fillable = [
        'modelo_id', 'general', 'subconcepto', 'descripcion'
    ];

    public function modelo(){
        return $this->belongsTo('App\Modelo','modelo_id');
    }
}
