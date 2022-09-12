<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecificationLote extends Model
{
    protected $fillable = [
        'lote_id', 'general', 'subconcepto', 'descripcion'
    ];

    public function lote(){
        return $this->belongsTo('App\Lote','lote_id');
    }
}
