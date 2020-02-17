<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Dato_extra;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    public function estad_datos_extra(Request $request)
    {
        //if(!$request->ajax())return redirect('/');
        
        $proyecto = $request->buscar;
        $etapa = $request->b_etapa;
        $fecha = $request->fecha;
        $fecha2 = $request->fecha2;

        $actual = Carbon::now();

        $participantes = (object) array('unParticipante' => 0, 'dosParticipantes' => 0);
        $genero = (object) array('hombres' => 0, 'mujeres' => 0);
        $autos = (object) array('sinAuto' => 0, 'unAuto' => 0, 'dosAuto' => 0, 'tresAuto' => 0, 'cuatroAuto' => 0,);
        $edoCivil = (object) array(
                                'separacionBienes' => 0, 
                                'sociedadConyugal' => 0,
                                'divorciado' => 0,
                                'soltero' => 0,
                                'unionLibre' => 0,
                                'viudo' => 0,
                                'otro' => 0
                                );


        if($fecha == '' || $fecha2 == ''){
                if($etapa == "" && $proyecto!=""){
                        $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',0)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
        
                        $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',1)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
        
                        $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',2)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
        
                        $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',3)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
                                        
                        $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','>',3)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
        
                        $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',1)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',2)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',3)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',4)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',5)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',6)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('clientes.edo_civil','=',7)
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                DB::raw('SUM(datos_extra.rang21) as sum21'))
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->get();
        
                        $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('personal.f_nacimiento','contratos.fecha')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)->distinct()->get();
        
                        $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.sexo')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('clientes.sexo','=','F')
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.coacreditado')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('clientes.coacreditado','=',1)
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.coacreditado')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('clientes.coacreditado','=',0)
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.sexo')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('clientes.sexo','=','M')
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.lugar_nacimiento')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                        $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.empresa')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->orderBy('clientes.empresa','asc')->distinct()->get();

                                if(sizeof($empresas)){
                                        $empresas_cliente=[];
                                        foreach($empresas as $er=>$empresa){
                                                $empresas_cliente[$er] = $empresa->empresa;
                                              $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('clientes.empresa','=',$empresa->empresa)
                                                ->where('contratos.status','=',3)->count();
                                        }
                                }
        
        
                                if(sizeof($origen)){
                                        $lugarNacimiento=[];
                                        foreach($origen as $er=>$lugar){
                                                $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.lugar_nacimiento')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                ->where('contratos.status','=',3)->count();
                                        }
                                }
                                
                        
                        $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.persona_discap','=',1)
                                ->where('contratos.status','=',3)
                                ->get()->count();
                        
                        $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.silla_ruedas','=',1)
                                ->where('contratos.status','=',3)
                                ->get()->count();
        
                        
                        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.mascota','=',0)
                                ->where('contratos.status','=',3)
                                ->get()->count();
        
                        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->select(
                                        DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                        DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                        DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                        DB::raw('SUM(datos_extra.num_perros) as perros')
                                        )
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->get();
                }else{
                        if($etapa!="" && $proyecto!=""){
                                $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',0)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
        
                                $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',1)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
        
                                $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',2)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
        
                                $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',3)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
                                                
                                $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','>',3)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
        
                                $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',1)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',2)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',3)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',4)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',5)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',6)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.edo_civil','=',7)
                                                ->where('contratos.status','=',3)
                                                ->count();
        
        
                                $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                        DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                        DB::raw('SUM(datos_extra.rang21) as sum21'))
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->get();
        
                                $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('personal.f_nacimiento','contratos.fecha')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)->distinct()->get();
        
                                $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.coacreditado')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.coacreditado','=',1)
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.coacreditado')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.coacreditado','=',0)
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.sexo')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.sexo','=','F')
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.sexo')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.sexo','=','M')
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.lugar_nacimiento')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                                $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.empresa')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->orderBy('clientes.empresa','asc')->distinct()->get();
        
                                        if(sizeof($empresas)){
                                                $empresas_cliente=[];
                                                foreach($empresas as $er=>$empresa){
                                                        $empresas_cliente[$er] = $empresa->empresa;
                                                      $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.empresa')
                                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.empresa','=',$empresa->empresa)
                                                        ->where('contratos.status','=',3)->count();
                                                }
                                        }
        
                                        if(sizeof($origen)){
                                                $lugarNacimiento=[];
                                                foreach($origen as $er=>$lugar){
                                                        $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                                      $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.lugar_nacimiento')
                                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                                }
                                        }
                                        
                                
                                $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.persona_discap','=',1)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
                                
                                $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.silla_ruedas','=',1)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
                        
                                
                                $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.mascota','=',0)
                                        ->where('contratos.status','=',3)
                                        ->get()->count();
                        
                                $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->select(
                                                DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                                DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                                DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                                DB::raw('SUM(datos_extra.num_perros) as perros')
                                                )
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->get();
                        }
                        else{
                                if($etapa!="" && $proyecto==""){
                                        $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',0)
                                                        ->where('contratos.status','=',3)
                                                        ->get()->count();
        
                                        $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',1)
                                                        ->where('contratos.status','=',3)
                                                        ->get()->count();
        
                                        $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',2)
                                                        ->where('contratos.status','=',3)
                                                        ->get()->count();
        
                                        $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',3)
                                                        ->where('contratos.status','=',3)
                                                        ->get()->count();
                                                        
                                        $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','>',3)
                                                        ->where('contratos.status','=',3)
                                                        ->get()->count();
        
                                        $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                        $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',1)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',2)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',3)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',4)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',5)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',6)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('clientes.edo_civil','=',7)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
        
                                        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                                DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                                DB::raw('SUM(datos_extra.rang21) as sum21'))
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->get();
                                        
                                        $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('personal.f_nacimiento','contratos.fecha')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)->distinct()->get();
        
                                        $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.lugar_nacimiento')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                                        $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->orderBy('clientes.empresa','asc')->distinct()->get();
                
                                                if(sizeof($empresas)){
                                                        $empresas_cliente=[];
                                                        foreach($empresas as $er=>$empresa){
                                                                $empresas_cliente[$er] = $empresa->empresa;
                                                              $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.empresa')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->where('clientes.empresa','=',$empresa->empresa)
                                                                ->where('contratos.status','=',3)->count();
                                                        }
                                                }
        
                                                if(sizeof($origen)){
                                                        $lugarNacimiento=[];
                                                        foreach($origen as $er=>$lugar){
                                                                $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.lugar_nacimiento')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                                ->where('contratos.status','=',3)
                                                                ->count();
                                                        }
                                                }
        
                                        $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.sexo')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.sexo','=','F')
                                                ->where('contratos.status','=',3)
                                                ->count();
                        
                                        $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.sexo')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.sexo','=','M')
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                        $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.coacreditado')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.coacreditado','=',1)
                                                ->where('contratos.status','=',3)
                                                ->count();
                
                                        $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.coacreditado')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('clientes.coacreditado','=',0)
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                                if(sizeof($origen)){
                                                        foreach($origen as $er=>$lugar){
                                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.lugar_nacimiento')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                                ->where('contratos.status','=',3)->count();
                                                        }
                                                }
                                
                                        $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.persona_discap','=',1)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
                                        
                                        $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.silla_ruedas','=',1)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
                                
                                        
                                        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.mascota','=',0)
                                                ->where('contratos.status','=',3)
                                                ->get()->count();
                                
                                        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->select(
                                                        DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                                        DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                                        DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                                        DB::raw('SUM(datos_extra.num_perros) as perros')
                                                        )
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->get();    
                                }
                        }
                
                }
        }
        else{
                if($etapa == "" && $proyecto!=""){

                        $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',0)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
        
                        $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',1)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
        
                        $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',2)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
        
                        $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','=',3)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
                                        
                        $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('datos_extra.num_vehiculos','>',3)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
        
                        $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',1)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',2)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',3)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',4)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',5)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',6)
                                        ->where('contratos.status','=',3)
                                        ->count();
                        $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.edo_civil')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('clientes.edo_civil','=',7)
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                DB::raw('SUM(datos_extra.rang21) as sum21'))
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('contratos.status','=',3)
                                ->get();
        
                        $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('personal.f_nacimiento','contratos.fecha')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('contratos.status','=',3)->distinct()->get();
        
                        $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.sexo')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('clientes.sexo','=','F')
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.coacreditado')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('clientes.coacreditado','=',1)
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.coacreditado')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('clientes.coacreditado','=',0)
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.sexo')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('clientes.sexo','=','M')
                                ->where('contratos.status','=',3)
                                ->count();
        
                        $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.lugar_nacimiento')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('contratos.status','=',3)
                                ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                        $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.empresa')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->where('contratos.status','=',3)
                                ->orderBy('clientes.empresa','asc')->distinct()->get();

                                if(sizeof($empresas)){
                                        $empresas_cliente=[];
                                        foreach($empresas as $er=>$empresa){
                                                $empresas_cliente[$er] = $empresa->empresa;
                                              $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.empresa','=',$empresa->empresa)
                                                ->where('contratos.status','=',3)->count();
                                        }
                                }
        
        
                                if(sizeof($origen)){
                                        $lugarNacimiento=[];
                                        foreach($origen as $er=>$lugar){
                                                $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.lugar_nacimiento')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                ->where('contratos.status','=',3)->count();
                                        }
                                }
                                
                        
                        $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.persona_discap','=',1)
                                ->where('contratos.status','=',3)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->get()->count();
                        
                        $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.silla_ruedas','=',1)
                                ->where('contratos.status','=',3)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->get()->count();
        
                        
                        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('datos_extra.mascota','=',0)
                                ->where('contratos.status','=',3)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->get()->count();
        
                        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->select(
                                        DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                        DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                        DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                        DB::raw('SUM(datos_extra.num_perros) as perros')
                                        )
                                ->where('lotes.fraccionamiento_id',$proyecto)
                                ->where('contratos.status','=',3)
                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                ->get();
                }else{
                        if($etapa!="" && $proyecto!=""){
                                $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',0)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
        
                                $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',1)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
        
                                $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',2)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
        
                                $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','=',3)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
                                                
                                $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.num_vehiculos','>',3)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
        
                                $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('contratos.status','=',3)
                                        ->count();
        
                                $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',1)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',2)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',3)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',4)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',5)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',6)
                                                ->where('contratos.status','=',3)
                                                ->count();
                                $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.edo_civil')
                                                ->where('lotes.fraccionamiento_id',$proyecto)
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.edo_civil','=',7)
                                                ->where('contratos.status','=',3)
                                                ->count();
        
        
                                $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                        DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                        DB::raw('SUM(datos_extra.rang21) as sum21'))
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get();
        
                                $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('personal.f_nacimiento','contratos.fecha')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])->distinct()->get();
        
                                $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.coacreditado')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.coacreditado','=',1)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->count();
        
                                $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.coacreditado')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.coacreditado','=',0)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->count();
        
                                $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.sexo')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.sexo','=','F')
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->count();
        
                                $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.sexo')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('clientes.sexo','=','M')
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->count();
        
                                $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.lugar_nacimiento')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                                $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                        ->select('clientes.empresa')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->where('contratos.status','=',3)
                                        ->orderBy('clientes.empresa','asc')->distinct()->get();
        
                                        if(sizeof($empresas)){
                                                $empresas_cliente=[];
                                                foreach($empresas as $er=>$empresa){
                                                        $empresas_cliente[$er] = $empresa->empresa;
                                                      $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.empresa')
                                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.empresa','=',$empresa->empresa)
                                                        ->where('contratos.status','=',3)->count();
                                                }
                                        }
        
                                        if(sizeof($origen)){
                                                $lugarNacimiento=[];
                                                foreach($origen as $er=>$lugar){
                                                        $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                                      $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.lugar_nacimiento')
                                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                                }
                                        }
                                        
                                
                                $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.persona_discap','=',1)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
                                
                                $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.silla_ruedas','=',1)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
                        
                                
                                $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('datos_extra.mascota','=',0)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get()->count();
                        
                                $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('contratos','creditos.id','=','contratos.id')
                                        ->select(
                                                DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                                DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                                DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                                DB::raw('SUM(datos_extra.num_perros) as perros')
                                                )
                                        ->where('lotes.fraccionamiento_id',$proyecto)
                                        ->where('lotes.etapa_id',$etapa)
                                        ->where('contratos.status','=',3)
                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                        ->get();
                        }
                        else{
                                if($etapa!="" && $proyecto==""){
                                        $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',0)
                                                        ->where('contratos.status','=',3)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->get()->count();
        
                                        $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',1)
                                                        ->where('contratos.status','=',3)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->get()->count();
        
                                        $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',2)
                                                        ->where('contratos.status','=',3)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->get()->count();
        
                                        $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','=',3)
                                                        ->where('contratos.status','=',3)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->get()->count();
                                                        
                                        $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('contratos','creditos.id','=','contratos.id')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->where('datos_extra.num_vehiculos','>',3)
                                                        ->where('contratos.status','=',3)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->get()->count();
        
                                        $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                        $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',1)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',2)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',3)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',4)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',5)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',6)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
                                        $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                        ->select('clientes.edo_civil')
                                                        ->where('lotes.etapa_id',$etapa)
                                                        ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                        ->where('clientes.edo_civil','=',7)
                                                        ->where('contratos.status','=',3)
                                                        ->count();
        
                                        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                                DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                                DB::raw('SUM(datos_extra.rang21) as sum21'))
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get();
                                        
                                        $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('personal.f_nacimiento','contratos.fecha')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('contratos.status','=',3)->distinct()->get();
        
                                        $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.lugar_nacimiento')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('contratos.status','=',3)
                                                ->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                                        $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('contratos.status','=',3)
                                                ->orderBy('clientes.empresa','asc')->distinct()->get();
                
                                                if(sizeof($empresas)){
                                                        $empresas_cliente=[];
                                                        foreach($empresas as $er=>$empresa){
                                                                $empresas_cliente[$er] = $empresa->empresa;
                                                              $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.empresa')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                                ->where('clientes.empresa','=',$empresa->empresa)
                                                                ->where('contratos.status','=',3)->count();
                                                        }
                                                }
        
                                                if(sizeof($origen)){
                                                        $lugarNacimiento=[];
                                                        foreach($origen as $er=>$lugar){
                                                                $lugarNacimiento[$er] = $lugar->lugar_nacimiento;
                                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.lugar_nacimiento')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                                ->where('contratos.status','=',3)
                                                                ->count();
                                                        }
                                                }
        
                                        $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.sexo')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.sexo','=','F')
                                                ->where('contratos.status','=',3)
                                                ->count();
                        
                                        $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.sexo')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.sexo','=','M')
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                        $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.coacreditado')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.coacreditado','=',1)
                                                ->where('contratos.status','=',3)
                                                ->count();
                
                                        $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.coacreditado')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->where('clientes.coacreditado','=',0)
                                                ->where('contratos.status','=',3)
                                                ->count();
        
                                                if(sizeof($origen)){
                                                        foreach($origen as $er=>$lugar){
                                                              $lugar->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                                ->select('clientes.lugar_nacimiento')
                                                                ->where('lotes.etapa_id',$etapa)
                                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                                ->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                                ->where('contratos.status','=',3)->count();
                                                        }
                                                }
                                
                                        $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.persona_discap','=',1)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
                                        
                                        $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.silla_ruedas','=',1)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
                                
                                        
                                        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('datos_extra.mascota','=',0)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get()->count();
                                
                                        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('contratos','creditos.id','=','contratos.id')
                                                ->select(
                                                        DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                                        DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                                        DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                                        DB::raw('SUM(datos_extra.num_perros) as perros')
                                                        )
                                                ->where('lotes.etapa_id',$etapa)
                                                ->where('contratos.status','=',3)
                                                ->whereBetween('contratos.fecha', [$fecha, $fecha2])
                                                ->get();    
                                }
                        }
                
                }
        }
        

        
        $mascotas[0]->sin_mascotas = $SinMascotas;
        $totalPersonas = $mascotas[0]->sin_mascotas + $mascotas[0]->sumMascota;
        $sinDiscap = $totalPersonas - $discapacitados;

        if($totalPersonas > 0){
                $mascotas[0]->promedioPerros = $mascotas[0]->perros/$totalPersonas;
                $promedioAutos = $mascotas[0]->totalAutos/$totalPersonas;
                $promedioAmasCasa = $mascotas[0]->totalAmaCasa/$totalPersonas;
        }else{
                $mascotas[0]->promedioPerros =0;
                $promedioAutos = 0;
                $promedioAmasCasa = 0;
        }

        if(sizeof($edadesVenta)){
                $diferencia = 0;
                $rango1=0;
                $rango2=0;
                $rango3=0;
                $rango4=0;
                $rango5=0;
                $rango6=0;
                $rango7=0;
                foreach($edadesVenta as $ep=>$det)
                {
                        setlocale(LC_TIME, 'es_MX.utf8');

                        $date = Carbon::parse($det->fecha);
                        $now = Carbon::now();
                        $diferencia = $date->diffInYears($now);

                        $fecha2 = new Carbon($det->fecha);
                        $det->edad =  Carbon::parse($det->f_nacimiento)->age - $diferencia;

                        if($det->edad >= 20 && $det->edad<26){
                                $rango1 ++;
                        }
                        if($det->edad >= 26 && $det->edad<31){
                                $rango2 ++;
                        }
                        if($det->edad >= 31 && $det->edad<41){
                                $rango3 ++;
                        }
                        if($det->edad >= 41 && $det->edad<51){
                                $rango4 ++;
                        }
                        if($det->edad >= 51 && $det->edad<61){
                                $rango5 ++;
                        }
                        if($det->edad >= 61 && $det->edad<71){
                                $rango6 ++;
                        }
                        if($det->edad >= 71){
                                $rango7 ++;
                        }

                        
                }
                
        }
 

        return [
                'lugarNacimiento'=>$lugarNacimiento,
                'empresas'=>$empresas_cliente,
                'empresasVentas'=>$empresas,
                'autos' => $autos,
                'total' => $total,
                'edadesVenta'=>$edadesVenta,
                'origen'=>$origen,
                'genero'=>$genero,
                'estadoCivil'=> $edoCivil,
                'participantes'=>$participantes,
                'edades'=>$edades,'mascotas'=>$mascotas, 
                'discap'=>$discapacitados, 
                'sinDiscap'=> $sinDiscap,
                'silla_ruedas'=>$silla_ruedas,
                'promedioAutos'=>$promedioAutos,
                'rango1'=> $rango1,
                'rango2'=> $rango2,
                'rango3'=> $rango3,
                'rango4'=> $rango4,
                'rango5'=> $rango5,
                'rango6'=> $rango6,
                'rango7'=> $rango7,
                'promedioAmasCasa'=>$promedioAmasCasa];      
    }

    public function resumenProyecto(Request $request){
        if(!$request->ajax())return redirect('/');
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;

        $to = Carbon::now();


        if($etapa!=''){

                $fracc = Etapa::select('fecha_ini_venta')->where('id','=',$etapa)->where('fraccionamiento_id','=',$proyecto)->get();
                $fecha = $fracc[0]->fecha_ini_venta;
                if($fecha){
                        $from = Carbon::createFromFormat('Y-m-d', $fecha);
                        $diff_in_months = $to->diffInMonths($from);
                }
                else{
                        $diff_in_months = 0;
                }
                

                $lotes = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('etapa_id','=',$etapa)->count();
                
                $lotesHabilitados = Lote::where('fraccionamiento_id','=',$proyecto)
                        ->where('etapa_id','=',$etapa)
                        ->where('habilitado','=',1)->count();

                $disponibles = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('etapa_id','=',$etapa)
                                ->where('habilitado','=',1)
                                ->where('contrato','=',0)->count();

                $vendidas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->count();

                $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select('lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',1)
                                ->where('contratos.integracion','=',0)->distinct()
                                ->count();
                
                $individualizadas = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)
                                ->where('i.elegido', '=', 1)
                                ->where('i.tipo_credito', '!=', 'Crédito Directo')
                                ->count();

                $indiviDirecto = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)
                                ->where('contratos.saldo','=',0)
                                ->where('i.elegido', '=', 1)
                                ->where('i.tipo_credito', '=', 'Crédito Directo')
                                ->count();

                $sumas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select(       DB::raw("SUM(creditos.precio_venta) as precio"),
                                                DB::raw("SUM(creditos.descuento_promocion) as descuento"),
                                                DB::raw("SUM(creditos.costo_paquete) as paquete"),
                                                DB::raw("SUM(contratos.total_pagar) as enganche"),
                                                DB::raw("SUM(contratos.monto_total_credito) as credito_netoSum"),
                                                DB::raw("SUM(contratos.saldo) as totSaldo")
                                        )
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.elegido', '=', 1)->get();

                $resContratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->join('personal as c', 'creditos.prospecto_id', '=', 'c.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select(       DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'creditos.fraccionamiento as proyecto',
                                                'creditos.etapa',
                                                'creditos.manzana',
                                                'creditos.num_lote',
                                                'contratos.fecha_status',
                                                'i.tipo_credito',
                                                'i.institucion', 
                                                'creditos.precio_venta',
                                                'creditos.descuento_promocion',
                                                'creditos.costo_paquete',
                                                'contratos.total_pagar',
                                                'contratos.saldo',
                                                'contratos.monto_total_credito',
                                                'lotes.calle','lotes.numero',
                                                'modelos.nombre as modelo'
                                        )
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->where('i.elegido', '=', 1)->paginate(20);

                $disponibles  = $disponibles + $contratos;
                $vendidas = $vendidas - $individualizadas - $indiviDirecto;
                $individualizadas = $individualizadas + $indiviDirecto;
        }
        else{
                $fracc = Fraccionamiento::select('fecha_ini_venta')->where('id','=',$proyecto)->get();
                $fecha = $fracc[0]->fecha_ini_venta;

                if($fecha){
                        $from = Carbon::createFromFormat('Y-m-d', $fecha);
                        $diff_in_months = $to->diffInMonths($from);
                }
                else{
                        $diff_in_months = 0;
                }

                $lotes = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->count();

                $lotesHabilitados = Lote::where('fraccionamiento_id','=',$proyecto)
                        ->where('habilitado','=',1)->count();

                $disponibles = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('contrato','=',0)
                                ->where('habilitado','=',1)->count();

                $vendidas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->count();

                $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select('lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',1)
                                ->where('contratos.integracion','=',0)->distinct()
                                ->count();
                
                $individualizadas = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)
                                ->where('i.tipo_credito', '!=', 'Crédito Directo')
                                ->where('i.elegido', '=', 1)
                                ->count();
                
                $indiviDirecto = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)
                                ->where('contratos.saldo','=',0)
                                ->where('i.elegido', '=', 1)
                                ->where('i.tipo_credito', '=', 'Crédito Directo')
                                ->count();

                $sumas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select(       DB::raw("SUM(creditos.precio_venta) as precio"),
                                                DB::raw("SUM(creditos.descuento_promocion) as descuento"),
                                                DB::raw("SUM(creditos.costo_paquete) as paquete"),
                                                DB::raw("SUM(contratos.total_pagar) as enganche"),
                                                DB::raw("SUM(contratos.monto_total_credito) as credito_netoSum"),
                                                DB::raw("SUM(contratos.saldo) as totSaldo")
                                        )
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.elegido', '=', 1)->get();

                $resContratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->join('personal as c', 'creditos.prospecto_id', '=', 'c.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select(       DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                'creditos.fraccionamiento as proyecto',
                                                'creditos.etapa',
                                                'creditos.manzana',
                                                'creditos.num_lote',
                                                'contratos.fecha_status',
                                                'i.tipo_credito',
                                                'i.institucion', 
                                                'creditos.precio_venta',
                                                'creditos.descuento_promocion',
                                                'creditos.costo_paquete',
                                                'contratos.total_pagar',
                                                'contratos.saldo',
                                                'contratos.monto_total_credito',
                                                'lotes.calle','lotes.numero',
                                                'modelos.nombre as modelo'
                                        )
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->where('i.elegido', '=', 1)->paginate(20);

                $disponibles  = $disponibles + $contratos;
                $vendidas = $vendidas - $individualizadas - $indiviDirecto;
                $individualizadas = $individualizadas + $indiviDirecto;
        }

        setlocale(LC_TIME, 'es_MX.utf8');
        if($fecha){
                $tiempo = new Carbon($fecha);
                $fecha = $tiempo->formatLocalized('%d de %B de %Y');
        }

        return[ 'lotes'=>$lotes, 
                'disponibles'=>$disponibles,
                'vendidas'=>$vendidas,
                'individualizadas'=>$individualizadas,
                'sumas'=>$sumas,
                'resContratos'=>$resContratos,
                'habilitados'=>$lotesHabilitados,
                'diferencia'=>$diff_in_months,
                'fecha_inicio'=>$fecha,
                'pagination' => [
                        'total'         => $resContratos->total(),
                        'current_page'  => $resContratos->currentPage(),
                        'per_page'      => $resContratos->perPage(),
                        'last_page'     => $resContratos->lastPage(),
                        'from'          => $resContratos->firstItem(),
                        'to'            => $resContratos->lastItem(),
                ],
        ];


    }

}
