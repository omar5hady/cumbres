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
use Excel;
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


                $conPerro = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.mascota','=',1)
                                ->where('datos_extra.num_perros','>',0)
                                ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $conPerro = $conPerro->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $conPerro = $conPerro->where('lotes.etapa_id',$etapa);

                                
                        if($fecha != '' && $fecha2 != '')
                                $conPerro = $conPerro->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                        $conPerro = $conPerro->get()->count();


                $autos->sinAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.num_vehiculos','=',0)
                                ->where('contratos.status','=',3);
                        if($proyecto != '')
                                $autos->sinAuto = $autos->sinAuto->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $autos->sinAuto = $autos->sinAuto->where('lotes.etapa_id',$etapa);
                                
                        if($fecha != '' && $fecha2 != '')
                                $autos->sinAuto = $autos->sinAuto->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $autos->sinAuto = $autos->sinAuto->get()->count();

                $autos->unAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.num_vehiculos','=',1)
                                ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $autos->unAuto = $autos->unAuto->where('lotes.fraccionamiento_id',$proyecto);

                        if($etapa != '')
                                $autos->unAuto = $autos->unAuto->where('lotes.etapa_id',$etapa);

                        if($fecha != '' && $fecha2 != '')
                                $autos->unAuto = $autos->unAuto->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $autos->unAuto = $autos->unAuto->get()->count();

                $autos->dosAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.num_vehiculos','=',2)
                                ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $autos->dosAuto = $autos->dosAuto->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $autos->dosAuto = $autos->dosAuto->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $autos->dosAuto = $autos->dosAuto->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $autos->dosAuto = $autos->dosAuto->get()->count();

                $autos->tresAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.num_vehiculos','=',3)
                                ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $autos->tresAuto = $autos->tresAuto->where('lotes.fraccionamiento_id',$proyecto);

                        if($etapa != '')
                                $autos->tresAuto = $autos->tresAuto->where('lotes.etapa_id',$etapa);

                        if($fecha != '' && $fecha2 != '')
                                $autos->tresAuto = $autos->tresAuto->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                        $autos->tresAuto = $autos->tresAuto->get()->count();
                                
                $autos->cuatroAuto = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->where('datos_extra.num_vehiculos','>',3)
                                ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $autos->cuatroAuto = $autos->cuatroAuto->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $autos->cuatroAuto = $autos->cuatroAuto->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $autos->cuatroAuto = $autos->cuatroAuto->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $autos->cuatroAuto = $autos->cuatroAuto->get()->count();

                $total = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('contratos.id');

                        if($proyecto != '')
                                $total = $total->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $total = $total->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $total = $total->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $total = $total->where('contratos.status','=',3)
                        ->count();

                $edoCivil->separacionBienes = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');
                                
                                if($proyecto != '')
                                        $edoCivil->separacionBienes = $edoCivil->separacionBienes->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->separacionBienes = $edoCivil->separacionBienes->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->separacionBienes = $edoCivil->separacionBienes->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                                $edoCivil->separacionBienes = $edoCivil->separacionBienes->where('clientes.edo_civil','=',1)
                                        ->where('contratos.status','=',3)
                                        ->count();
                                        
                $edoCivil->sociedadConyugal = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');

                                if($proyecto != '')
                                        $edoCivil->sociedadConyugal = $edoCivil->sociedadConyugal->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->sociedadConyugal = $edoCivil->sociedadConyugal->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->sociedadConyugal = $edoCivil->sociedadConyugal->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                                $edoCivil->sociedadConyugal = $edoCivil->sociedadConyugal->where('clientes.edo_civil','=',2)
                                        ->where('contratos.status','=',3)
                                        ->count();

                $edoCivil->divorciado = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');

                                if($proyecto != '')
                                        $edoCivil->divorciado = $edoCivil->divorciado->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->divorciado = $edoCivil->divorciado->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2)
                                        $edoCivil->divorciado = $edoCivil->divorciado->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                                $edoCivil->divorciado = $edoCivil->divorciado->where('clientes.edo_civil','=',3)
                                ->where('contratos.status','=',3)
                                ->count();

                $edoCivil->soltero = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');

                                if($proyecto != '')
                                        $edoCivil->soltero = $edoCivil->soltero->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->soltero = $edoCivil->soltero->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->soltero = $edoCivil->soltero->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                                $edoCivil->soltero = $edoCivil->soltero->where('clientes.edo_civil','=',4)
                                        ->where('contratos.status','=',3)
                                        ->count();

                $edoCivil->unionLibre = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');

                                if($proyecto != '')
                                        $edoCivil->unionLibre = $edoCivil->unionLibre->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->unionLibre = $edoCivil->unionLibre->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->unionLibre = $edoCivil->unionLibre->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                                $edoCivil->unionLibre = $edoCivil->unionLibre->where('clientes.edo_civil','=',5)
                                        ->where('contratos.status','=',3)
                                        ->count();

                $edoCivil->viudo = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');

                                if($proyecto != '')
                                        $edoCivil->viudo = $edoCivil->viudo->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->viudo = $edoCivil->viudo->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->viudo = $edoCivil->viudo->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                                $edoCivil->viudo = $edoCivil->viudo->where('clientes.edo_civil','=',6)
                                ->where('contratos.status','=',3)
                                ->count();

                $edoCivil->otro = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->select('clientes.edo_civil');
                                if($proyecto != '')
                                        $edoCivil->otro = $edoCivil->otro->where('lotes.fraccionamiento_id',$proyecto);
                                if($etapa != '')
                                        $edoCivil->otro = $edoCivil->otro->where('lotes.etapa_id',$etapa);
                                if($fecha != '' && $fecha2 != '')
                                        $edoCivil->otro = $edoCivil->otro->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                                $edoCivil->otro = $edoCivil->otro->where('clientes.edo_civil','=',7)
                                ->where('contratos.status','=',3)
                                ->count();


                $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                DB::raw('SUM(datos_extra.rang21) as sum21'))
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $edades = $edades->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $edades = $edades->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $edades = $edades->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $edades = $edades->get();

                $edadesVenta = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('personal.f_nacimiento','contratos.fecha')
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $edadesVenta = $edadesVenta->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $edadesVenta = $edadesVenta->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $edadesVenta = $edadesVenta->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                        $edadesVenta = $edadesVenta->distinct()->get();

                $participantes->dosParticipantes = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.coacreditado')
                        ->where('clientes.coacreditado','=',1)
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $participantes->dosParticipantes = $participantes->dosParticipantes->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $participantes->dosParticipantes = $participantes->dosParticipantes->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $participantes->dosParticipantes = $participantes->dosParticipantes->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $participantes->dosParticipantes = $participantes->dosParticipantes->count();

                $participantes->unParticipante = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.coacreditado')
                        ->where('clientes.coacreditado','=',0)
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $participantes->unParticipante = $participantes->unParticipante->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $participantes->unParticipante = $participantes->unParticipante->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $participantes->unParticipante = $participantes->unParticipante->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $participantes->unParticipante = $participantes->unParticipante->count();

                $genero->mujeres = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.sexo')
                        ->where('clientes.sexo','=','F')
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $genero->mujeres = $genero->mujeres->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $genero->mujeres = $genero->mujeres->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $genero->mujeres = $genero->mujeres->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $genero->mujeres = $genero->mujeres->count();

                $genero->hombres = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.sexo')
                        ->where('clientes.sexo','=','M')
                        ->where('contratos.status','=',3);
                        if($proyecto != '')
                                $genero->hombres = $genero->hombres->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $genero->hombres = $genero->hombres->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2!= '')
                                $genero->hombres = $genero->hombres->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $genero->hombres = $genero->hombres->count();

                $origen = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.lugar_nacimiento')
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $origen = $origen->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $origen = $origen->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $origen = $origen->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $origen = $origen->orderBy('clientes.lugar_nacimiento','asc')->distinct()->get();

                $colonias = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('personal.colonia')
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $colonias = $colonias->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $colonias = $colonias->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $colonias = $colonias->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $colonias = $colonias->orderBy('personal.colonia','asc')->distinct()->get();

                        if(sizeof($colonias)){
                                $colonias_cliente=[];
                                foreach($colonias as $er=>$colonia){
                                        $colonias_cliente[$er] = $colonia->colonia;
                                        $colonia->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('personal.colonia')
                                                ->where('contratos.status','=',3);

                                                if($proyecto != '')
                                                        $colonia->num = $colonia->num->where('lotes.fraccionamiento_id',$proyecto);
                                                if($etapa != '')
                                                        $colonia->num = $colonia->num->where('lotes.etapa_id',$etapa);
                                                if($fecha != '' && $fecha2 != '')
                                                        $colonia->num = $colonia->num->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                                
                                                $colonia->num = $colonia->num->where('personal.colonia','=',$colonia->colonia)
                                                ->count();
                                }
                        }


                $empresas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->select('clientes.empresa')
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $empresas = $empresas->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $empresas = $empresas->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $empresas = $empresas->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $empresas = $empresas->orderBy('clientes.empresa','asc')->distinct()->get();

                        if(sizeof($empresas)){
                                $empresas_cliente=[];
                                foreach($empresas as $er=>$empresa){
                                        $empresas_cliente[$er] = $empresa->empresa;
                                        $empresa->num = Contrato::join('creditos','contratos.id','=','creditos.id')
                                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                                ->select('clientes.empresa')
                                                ->where('contratos.status','=',3);

                                                if($proyecto != '')
                                                        $empresa->num = $empresa->num->where('lotes.fraccionamiento_id',$proyecto);
                                                if($etapa != '')
                                                        $empresa->num = $empresa->num->where('lotes.etapa_id',$etapa);
                                                if($fecha != '' && $fecha2 != '')
                                                        $empresa->num = $empresa->num->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                                
                                                $empresa->num = $empresa->num->where('clientes.empresa','=',$empresa->empresa)
                                                ->count();
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
                                                ->where('contratos.status','=',3);

                                                if($proyecto != '')
                                                        $lugar->num = $lugar->num->where('lotes.fraccionamiento_id',$proyecto);
                                                if($etapa != '')
                                                        $lugar->num = $lugar->num->where('lotes.etapa_id',$etapa);
                                                if($fecha != '' && $fecha2 != '')
                                                        $lugar->num = $lugar->num->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                                
                                                $lugar->num = $lugar->num->where('clientes.lugar_nacimiento','=',$lugar->lugar_nacimiento)
                                                ->count();
                                }
                        }
                        
                
                $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->where('datos_extra.persona_discap','=',1)
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $discapacitados = $discapacitados->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $discapacitados = $discapacitados->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $discapacitados = $discapacitados->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $discapacitados = $discapacitados->get()->count();
                
                $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->where('datos_extra.silla_ruedas','=',1)
                        ->where('contratos.status','=',3);
                        if($proyecto != '')
                                $silla_ruedas = $silla_ruedas->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $silla_ruedas = $silla_ruedas->where('lotes.etapa_id',$etapa);
                        if($fecha != '')
                                $silla_ruedas = $silla_ruedas->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                                
                        $silla_ruedas = $silla_ruedas->get()->count();
        
                
                $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->where('datos_extra.mascota','=',0)
                        ->where('contratos.status','=',3);

                        if($proyecto != '')
                                $SinMascotas = $SinMascotas->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $SinMascotas = $SinMascotas->where('lotes.etapa_id',$etapa);
                        if($fecha != '' && $fecha2 != '')
                                $SinMascotas = $SinMascotas->whereBetween('contratos.fecha', [$fecha, $fecha2]);
                        
                        $SinMascotas = $SinMascotas->get()->count();
        
                $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->select(
                                DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                                DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                                DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                                DB::raw('SUM(datos_extra.num_perros) as perros')
                                )
                        ->where('contratos.status','=',3);
                        if($proyecto != '')
                                $mascotas = $mascotas->where('lotes.fraccionamiento_id',$proyecto);
                        if($etapa != '')
                                $mascotas = $mascotas->where('lotes.etapa_id',$etapa);
                        
                        if($fecha != '' && $fecha2 != '')
                                $mascotas = $mascotas->whereBetween('contratos.fecha', [$fecha, $fecha2]);

                        $mascotas = $mascotas->get();
        
        
        $mascotas[0]->sin_mascotas = $SinMascotas;
        $totalPersonas = $mascotas[0]->sin_mascotas + $mascotas[0]->sumMascota;
        $sinDiscap = $totalPersonas - $discapacitados;

        if($totalPersonas > 0){
                $mascotas[0]->promedioPerros = $mascotas[0]->perros/$totalPersonas;
                $promedioAutos = $mascotas[0]->totalAutos/$totalPersonas;
                $promedioAmasCasa = $mascotas[0]->totalAmaCasa;
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
                'colonias_cliente'=> $colonias_cliente,
                'colonias'=>$colonias,
                'genero'=>$genero,
                'estadoCivil'=> $edoCivil,
                'participantes'=>$participantes,
                'edades'=>$edades,'mascotas'=>$mascotas, 
                'conPerro'=>$conPerro,
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


        $vendidasFin = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->select('contratos.fecha')
                        ->where('lotes.fraccionamiento_id','=',$proyecto);

                        if($etapa != '')
                                $vendidasFin = $vendidasFin->where('lotes.etapa_id','=',$etapa);
                        
                        $vendidasFin = $vendidasFin->where('contratos.status','=',3)
                        ->orderBy('contratos.fecha','desc')
                        ->get();
        if($etapa=='')
                $fracc = Fraccionamiento::select('fecha_ini_venta')->where('id','=',$proyecto)->get();
                
        else
                $fracc = Etapa::select('fecha_ini_venta')->where('id','=',$etapa)->where('fraccionamiento_id','=',$proyecto)->get();

        $fecha = $fracc[0]->fecha_ini_venta;

        if($fecha){
                $to = Carbon::createFromFormat('Y-m-d', $vendidasFin[0]->fecha);
                $from = Carbon::createFromFormat('Y-m-d', $fecha);
                $diff_in_months = $to->diffInMonths($from);
        }
        else{
                $diff_in_months = 0;
        }
        

        $lotes = Lote::where('fraccionamiento_id','=',$proyecto);
                if($etapa!='')
                        $lotes = $lotes->where('etapa_id','=',$etapa);        
                $lotes =$lotes->count();
        
        $lotesHabilitados = Lote::where('fraccionamiento_id','=',$proyecto);
                if($etapa!='')
                        $lotesHabilitados = $lotesHabilitados->where('etapa_id','=',$etapa);        
                $lotesHabilitados = $lotesHabilitados->where('habilitado','=',1)->count();

        $disponibles = Lote::where('fraccionamiento_id','=',$proyecto);
                if($etapa!='')
                        $disponibles = $disponibles->where('etapa_id','=',$etapa);
                $disponibles = $disponibles->where('habilitado','=',1)
                        ->where('contrato','=',0)->count();

        $vendidas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa!='')
                                $vendidas = $vendidas->where('lotes.etapa_id','=',$etapa);
                        $vendidas = $vendidas->where('contratos.status','=',3)->count();

        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->select('lotes.id')
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa!='')
                                $contratos = $contratos->where('lotes.etapa_id','=',$etapa);
                        $contratos = $contratos->where('contratos.status','=',1)
                                ->where('contratos.integracion','=',0)->distinct()
                                ->count();
        
        $individualizadas = Expediente::join('contratos','expedientes.id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa!='')
                                $individualizadas = $individualizadas->where('lotes.etapa_id','=',$etapa);
                        $individualizadas = $individualizadas->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)
                                ->where('expedientes.liquidado','=',1)
                                ->where('i.elegido', '=', 1)
                                ->where('i.tipo_credito', '!=', 'Crédito Directo')
                                ->distinct('contratos.id')
                                ->count();

        $indiviDirecto = Expediente::join('contratos','expedientes.id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa != '')
                                $indiviDirecto = $indiviDirecto->where('lotes.etapa_id','=',$etapa);
                        $indiviDirecto = $indiviDirecto->where('contratos.status','=',3)
                                ->where('contratos.integracion','=',1)                        
                                ->where('contratos.saldo','<=',0)
                                ->where('i.elegido', '=', 1)
                                ->where('i.tipo_credito', '=', 'Crédito Directo')
                                ->distinct('contratos.id')
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
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa != '')
                                $sumas = $sumas->where('lotes.etapa_id','=',$etapa);
                        $sumas = $sumas->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.elegido', '=', 1)->get();

        $sumaDirecto = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->select(       
                                        DB::raw("SUM(contratos.total_pagar) as enganche")
                                )
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa != '')
                                $sumaDirecto = $sumaDirecto->where('lotes.etapa_id','=',$etapa);
                        $sumaDirecto = $sumaDirecto->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.elegido', '=', 1)
                                ->where('inst_seleccionadas.tipo_credito', '=', 'Crédito Directo')->get();
                                
        $resContratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                        //->leftJoin('expedientes','contratos.id','=','expedientes.id')
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
                                        'contratos.monto_total_credito','lotes.interior',
                                        'lotes.calle','lotes.numero',//'expedientes.fecha_firma_esc',
                                        'modelos.nombre as modelo','contratos.fecha_audit','contratos.id'
                                )
                        ->when($request->bAudit,function($query, $audit){
                                        if($audit == 1){
                                                $query->whereNull('contratos.fecha_audit')->get();
                                        }elseif($audit == 2) $query->whereNotNull('contratos.fecha_audit')->get();
                                }
                        )
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                        if($etapa != '')
                                $resContratos = $resContratos->where('lotes.etapa_id','=',$etapa);
                        $resContratos = $resContratos->where('contratos.status','=',3)
                                ->where('i.elegido', '=', 1)->paginate(20);

        if(sizeOf($resContratos)){
                foreach($resContratos as $index => $contrato){
                        $contrato->fecha_firma_esc ='';
                        $expedientes = Expediente::select('fecha_firma_esc')->where('id','=',$contrato->id)->get();
                        if(sizeof($expedientes))
                                $contrato->fecha_firma_esc = $expedientes[0]->fecha_firma_esc;
                }
        }

        $disponibles  = $disponibles + $contratos;
        $vendidas = $vendidas - $individualizadas - $indiviDirecto;
        $individualizadas = $individualizadas + $indiviDirecto;

        

        setlocale(LC_TIME, 'es_MX.utf8');
        if($fecha){
                $tiempo = new Carbon($fecha);
                $fecha = $tiempo->formatLocalized('%d de %B de %Y');
        }

        return[ 'lotes'=>$lotes, 
                'disponibles'=>$disponibles,
                'vendidas'=>$vendidas,
                'contratos'=>$contratos,
                'individualizadas'=>$individualizadas,
                'sumas'=>$sumas,
                'directo'=>$sumaDirecto,
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

    public function excelResumen(Request $request){
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;

        $query = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->leftJoin('expedientes','contratos.id','=','expedientes.id')
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
                                        'contratos.monto_total_credito','lotes.interior',
                                        'lotes.calle','lotes.numero','expedientes.fecha_firma_esc',
                                        'modelos.nombre as modelo'
                );

        if($etapa != ''){
                $resContratos = $query
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('contratos.status','=',3)
                                ->where('i.elegido', '=', 1)->get();
        }
        else{
                $resContratos = $query
                                ->where('lotes.fraccionamiento_id','=',$proyecto)
                                ->where('contratos.status','=',3)
                                ->where('i.elegido', '=', 1)->get();
        }

        return Excel::create('Resumen', function($excel) use ($resContratos){
                $excel->sheet('Resumen', function($sheet) use ($resContratos){
                    
                    $sheet->row(1, [
                        'Proyecto', 'Etapa', 'Manzana',
                        '# Lote', 'Modelo','Dirección', 'Venta', 
                        'Cliente', 'Institución', 'Tipo Crédito',
                        'Firma escrituras','Precio venta', 'Enganche',
                        'Crédito', 'Saldo'
                    ]);

                    $sheet->setColumnFormat(array(
                        'L' => '$#,##0.00',
                        'M' => '$#,##0.00',
                        'N' => '$#,##0.00',
                        'O' => '$#,##0.00',
                    ));


                    $sheet->cells('A1:O1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(13);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    
                    $cont=1;

                    foreach($resContratos as $index => $contrato) {
                        $cont++;

                        $direccion = $contrato->calle.' Num. '.$contrato->numero;

                        $precio_venta = $contrato->precio_venta - $contrato->descuento_promocion + $contrato->costo_paquete;

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha_venta = new Carbon($contrato->fecha_status);
                        $contrato->fecha_status = $fecha_venta->formatLocalized('%d de %B de %Y');

                        if($contrato->fecha_firma_esc != NULL){
                                $fecha = new Carbon($contrato->fecha_firma_esc);
                                $contrato->fecha_firma_esc = $fecha->formatLocalized('%d de %B de %Y');
                        }
                        
                        $sheet->row($index+2, [
                            $contrato->proyecto, 
                            $contrato->etapa,
                            $contrato->manzana,
                            $contrato->num_lote,
                            $contrato->modelo,
                            $direccion,
                            $contrato->fecha_status,
                            $contrato->nombre_cliente,
                            $contrato->institucion,
                            $contrato->tipo_credito,
                            $contrato->fecha_firma_esc,
                            $precio_venta,
                            $contrato->total_pagar,
                            $contrato->monto_total_credito,
                            $contrato->saldo

                        ]);	
                    }
                    $num='A1:O' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
                }
        )->download('xls');
    }

}
