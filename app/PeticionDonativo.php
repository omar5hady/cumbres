<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeticionDonativo extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'titulo',
        'user_id',
        'status',
        'picture'
    ];

    public function usuario(){
        return $this->belongsTo('App\Personal','user_id');
    }
}
