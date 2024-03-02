<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $table = 'vacations';
    protected $fillable = [
        'id', 'user_id', 'total_dias', 'dias_tomados', 'saldo', 'anio', 'status'
    ];

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
