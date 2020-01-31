<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'vendedores';
    protected $fillable = [
        'id','supervisor_id','inmobiliaria','tipo','esquema'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    public function cliente(){
        return $this->hasMany('App\Cliente');
    }

    public function apartado(){
        return $this->hasMany('App\Apartado');
    }

    public function comision(){
        return $this->hasMany('App\Comision');
    }
}
