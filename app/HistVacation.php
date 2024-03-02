<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistVacation extends Model
{
    protected $table = 'hist_vacations';
    protected $fillable = [
        'id', 'user_id', 'f_ini', 'f_fin', 'dias_tomados', 'status', 'nota'
    ];

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function mediosDias(){
        return $this->hasMany('App\MedioDia');
    }
}
