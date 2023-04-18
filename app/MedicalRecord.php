<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HistMedicalRecords;

class MedicalRecord extends Model
{
    protected $fillable = [
        'user_id',
        'alerta',
        'tipo_sangre',
        'estatura',
        'alergias',
        'regimen_alimenticio'
    ];

    public function persona(){
        return $this->belongsTo('App\Personal','user_id');
    }

    public function historial(Request $request){
        $historial = HistMedicalRecords::where('record_id','=',$this->id);
        $historial = $historial->orderBy('fecha','desc')->paginate(1);
        return $historial;
    }
}
