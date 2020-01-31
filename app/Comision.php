<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $table = 'comisiones';
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'mes', 'anio', 'total', 'num_ventas', 'num_cancelaciones',
        'cobrado','asesor_id','bono','aPagar'
    ];

    public $timestamps = false;

    public function vendedor()
    {
        return $this->belongsTo('App\Vendedor');
    }

    public function det_comision()
    {
        return $this->hasMany('App\Det_comision');
    }
}
