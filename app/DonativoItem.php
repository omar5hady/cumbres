<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HistDonativo;

class DonativoItem extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'descripcion',
        'titulo',
        'user_id',
        'status',
        'picture'
    ];

    public function usuario(){
        return $this->belongsTo('App\Personal','user_id');
    }

    public function historial(){
        $historial = HistDonativo::join('personal as p','hist_donativos.user_id','=','p.id')
            ->select('hist_donativos.*','p.nombre','p.apellidos')
            ->where('hist_donativos.item_id','=',$this->id);
        $historial = $historial->orderBy('hist_donativos.created_at','desc')->get();
        return $historial;
    }
}
