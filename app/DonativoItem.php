<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HistDonativo;
use Auth;

class DonativoItem extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'descripcion',
        'titulo',
        'f_entrega',
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
        $historial = $historial->orderBy('hist_donativos.status','desc')->get();
        return $historial;
    }

    public function findMe(){
        return HistDonativo::where('item_id','=',$this->id)
            ->where('user_id','=',Auth::user()->id)->first();
    }

    public function elegido(){
        return HistDonativo::join('personal','personal.id','=','hist_donativos.user_id')
            ->select('hist_donativos.id','personal.nombre','personal.apellidos')
            ->where('item_id','=',$this->id)
            ->where('status','=',2)->first();
    }
}
