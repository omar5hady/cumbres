<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistMedicalRecords extends Model
{
    protected $primaryKey = 'id'; //Referenciar la llave primaria
    protected $fillable = [
        'record_id',
        'fecha',
        'peso',
        'imc',
        'tratamiento_act',
        'medic_controlado',
        'observacion',
        //HISTORIAL MEDICO
        'diabetes',
        'diabetes_esp',
        'hipertension',
        'hipertension_esp',
        'epileptico',
        'epileptico_esp',
        'cardiaco',
        'cardiaco_esp',
        'mareos',
        'mareos_esp',
        'asma',
        'asma_esp',
        'toxicomanias',
        'toxicomanias_esp',
        'cirugia',
        'cirugia_esp',
        'embarazo',
        'embarazo_esp',
        'transfusion',
        'transfusion_esp',
        'lesion_musculo',
        'lesion_musculo_esp',
        'ortopedicos',
        'ortopedicos_esp',
        'respiratorios',
        'respiratorios_esp',
        'oftalmicos',
        'oftalmicos_esp',
        'nariz',
        'nariz_esp',
        'neurologicos',
        'neurologicos_esp',
        'hematologicos',
        'hematologicos_esp',
        'hepaticos',
        'hepaticos_esp',
        'digestivo',
        'digestivo_esp',
        'tiroideo',
        'tiroideo_esp',
        'dermatologico',
        'dermatologico_esp',
        'inmunologico',
        'inmunologico_esp',
        'urinario',
        'urinario_esp',
        'covid',
        'covid_esp',
        //PSICOLOGICOS/PSIQUIATRICOS
        'cambio_alimentacion',
        'cambio_alimentacion_esp',
        'aislamiento',
        'aislamiento_esp',
        'sensacion_vacio',
        'sensacion_vacio_esp',
        'desesperanza',
        'desesperanza_esp',
        'irritabilidad',
        'irritabilidad_esp',
        'pensamientos_cabeza',
        'pensamientos_cabeza_esp',
        'lastimarse',
        'lastimarse_esp',
        'alt_sueno',
        'alt_sueno_esp',
        'agotamiento',
        'agotamiento_esp',
        'dolores',
        'dolores_esp',
        'aumento_toxic',
        'aumento_toxic_esp',
        'humor',
        'humor_esp',
        'voces',
        'voces_esp',
        'dif_tareas',
        'dif_tareas_esp'
    ];
    public $timestamps = false;

    public function medical_record(){
        return $this->belongsTo('App\Personal','user_id');
    }
}
