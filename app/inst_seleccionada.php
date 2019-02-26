<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inst_seleccionada extends Model
{
    
    protected $table = 'inst_seleccionadas';
    protected $fillable = [
        'credito_id', 'tipo_credito', 'institucion'
    ];
 
 
    public function Credito()
    {
        return $this->belongsTo('App\Credito');
    }
}
