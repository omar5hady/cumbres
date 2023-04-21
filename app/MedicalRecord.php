<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HistMedicalRecords;
use App\MedicalAffiliation;

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

    public function usuario(){
        return $this->belongsTo('App\Personal','user_id');
    }

    public function historial(){
        $historial = HistMedicalRecords::where('record_id','=',$this->id);
        $historial = $historial->orderBy('fecha','desc')->paginate(1);
        return $historial;
    }

    public function afiliaciones(){
        return MedicalAffiliation::where('record_id','=',$this->id)->get();
    }
}
