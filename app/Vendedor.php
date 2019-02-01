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
        'id','supervisor_id'
    ];

    public function persona(){
        return $this->belongsTo('App\Persona');
    }
}
