<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalRecordResource;

use Auth;
use DB;
use Carbon\Carbon;

use App\MedicalRecord;
use App\HistMedicalRecords;

class MedicalRecordController extends Controller
{
    public function index(Request $request){
        return MedicalRecordResource::collection(MedicalRecord::where('user_id','=',$request->user_id)->get());
    }

    public function store(Request $request){
        $record = $request->medicalRecord;
        $histMedico = $request->histMedico;
        if($record['id'] == null)
            $medicalRecord = new MedicalRecord();
        else
            $medicalRecord = MedicalRecord::findOrFail($record['id']);

        $medicalRecord->user_id = $record['user_id'];
        $medicalRecord->alerta = $record['alerta'];
        $medicalRecord->tipo_sangre = $record['tipo_sangre'];
        $medicalRecord->estatura = $record['estatura'];
        $medicalRecord->alergias = $record['alergias'];
        $medicalRecord->regimen_alimenticio = $record['regimen_alimenticio'];
        $medicalRecord->save();

        $histMedico['imc'] =$histMedico['peso']/ ($medicalRecord->estatura*$medicalRecord->estatura);

        $this->storeHist($medicalRecord->id, $histMedico);
    }

    private function storeHist($id, $histMedico){
        $hist = new HistMedicalRecords();
        $hist->record_id = $id;
        $hist->fecha = Carbon::now();
        $hist->peso = $histMedico['peso'];
        $hist->imc = $histMedico['imc'];
        $hist->tratamiento_act = $histMedico['tratamiento_act'];
        $hist->medic_controlado = $histMedico['medic_controlado'];
        $hist->observacion = $histMedico['observacion'];
        //HISTORIAL MEDICO
        $hist->diabetes = $histMedico['diabetes'];
        $hist->diabetes_esp = $histMedico['diabetes_esp'];
        $hist->hipertension = $histMedico['hipertension'];
        $hist->hipertension_esp = $histMedico['hipertension_esp'];
        $hist->epileptico = $histMedico['epileptico'];
        $hist->epileptico_esp = $histMedico['epileptico_esp'];
        $hist->cardiaco = $histMedico['cardiaco'];
        $hist->cardiaco_esp = $histMedico['cardiaco_esp'];
        $hist->mareos = $histMedico['mareos'];
        $hist->mareos_esp = $histMedico['mareos_esp'];
        $hist->asma = $histMedico['asma'];
        $hist->asma_esp = $histMedico['asma_esp'];
        $hist->toxicomanias = $histMedico['toxicomanias'];
        $hist->toxicomanias_esp = $histMedico['toxicomanias_esp'];
        $hist->cirugia = $histMedico['cirugia'];
        $hist->cirugia_esp = $histMedico['cirugia_esp'];
        $hist->embarazo = $histMedico['embarazo'];
        $hist->embarazo_esp = $histMedico['embarazo_esp'];
        $hist->transfusion = $histMedico['transfusion'];
        $hist->transfusion_esp = $histMedico['transfusion_esp'];
        $hist->lesion_musculo = $histMedico['lesion_musculo'];
        $hist->lesion_musculo_esp = $histMedico['lesion_musculo_esp'];
        $hist->ortopedicos = $histMedico['ortopedicos'];
        $hist->ortopedicos_esp = $histMedico['ortopedicos_esp'];
        $hist->respiratorios = $histMedico['respiratorios'];
        $hist->respiratorios_esp = $histMedico['respiratorios_esp'];
        $hist->oftalmicos = $histMedico['oftalmicos'];
        $hist->oftalmicos_esp = $histMedico['oftalmicos_esp'];
        $hist->nariz = $histMedico['nariz'];
        $hist->nariz_esp = $histMedico['nariz_esp'];
        $hist->neurologicos = $histMedico['neurologicos'];
        $hist->neurologicos_esp = $histMedico['neurologicos_esp'];
        $hist->hematologicos = $histMedico['hematologicos'];
        $hist->hematologicos_esp = $histMedico['hematologicos_esp'];
        $hist->hepaticos = $histMedico['hepaticos'];
        $hist->hepaticos_esp = $histMedico['hepaticos_esp'];
        $hist->digestivo = $histMedico['digestivo'];
        $hist->digestivo_esp = $histMedico['digestivo_esp'];
        $hist->tiroideo = $histMedico['tiroideo'];
        $hist->tiroideo_esp = $histMedico['tiroideo_esp'];
        $hist->dermatologico = $histMedico['dermatologico'];
        $hist->dermatologico_esp = $histMedico['dermatologico_esp'];
        $hist->inmunologico = $histMedico['inmunologico'];
        $hist->inmunologico_esp = $histMedico['inmunologico_esp'];
        $hist->urinario = $histMedico['urinario'];
        $hist->urinario_esp = $histMedico['urinario_esp'];
        $hist->covid = $histMedico['covid'];
        $hist->covid_esp = $histMedico['covid_esp'];
        //PSICOLOGICOS/PSIQUIATRICOS
        $hist->cambio_alimentacion = $histMedico['cambio_alimentacion'];
        $hist->cambio_alimentacion_esp = $histMedico['cambio_alimentacion_esp'];
        $hist->aislamiento = $histMedico['aislamiento'];
        $hist->aislamiento_esp = $histMedico['aislamiento_esp'];
        $hist->sensacion_vacio = $histMedico['sensacion_vacio'];
        $hist->sensacion_vacio_esp = $histMedico['sensacion_vacio_esp'];
        $hist->desesperanza = $histMedico['desesperanza'];
        $hist->desesperanza_esp = $histMedico['desesperanza_esp'];
        $hist->irritabilidad = $histMedico['irritabilidad'];
        $hist->irritabilidad_esp = $histMedico['irritabilidad_esp'];
        $hist->pensamientos_cabeza = $histMedico['pensamientos_cabeza'];
        $hist->pensamientos_cabeza_esp = $histMedico['pensamientos_cabeza_esp'];
        $hist->lastimarse = $histMedico['lastimarse'];
        $hist->lastimarse_esp = $histMedico['lastimarse_esp'];
        $hist->alt_sueno = $histMedico['alt_sueno'];
        $hist->alt_sueno_esp = $histMedico['alt_sueno_esp'];
        $hist->agotamiento = $histMedico['agotamiento'];
        $hist->agotamiento_esp = $histMedico['agotamiento_esp'];
        $hist->dolores = $histMedico['dolores'];
        $hist->dolores_esp = $histMedico['dolores_esp'];
        $hist->aumento_toxic = $histMedico['aumento_toxic'];
        $hist->aumento_toxic_esp = $histMedico['aumento_toxic_esp'];
        $hist->humor = $histMedico['humor'];
        $hist->humor_esp = $histMedico['humor_esp'];
        $hist->voces = $histMedico['voces'];
        $hist->voces_esp = $histMedico['voces_esp'];
        $hist->dif_tareas = $histMedico['dif_tareas'];
        $hist->dif_tareas_esp = $histMedico['dif_tareas_esp'];
        $hist->save();
    }
}
