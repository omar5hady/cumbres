<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalogo_bono extends Model
{
    protected $table = 'catalogo_bonos';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'fecha_ini',
        'fecha_fin',
        'descripcion',
        'monto',
        'etapa_id'
    ];
 
    public function etapa()
    {
        return $this->belongsTo('App\Etapa');
    }
}
