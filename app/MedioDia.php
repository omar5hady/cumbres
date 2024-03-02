<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedioDia extends Model
{
    protected $table = 'medio_dias';
    protected $fillable = [
        'id', 'hist_id', 'fecha'
    ];

    public function Historial()
    {
        return $this->belongsTo('App\HistVacation', 'hist_id');
    }
}
