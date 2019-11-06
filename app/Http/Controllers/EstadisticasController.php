<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Dato_extra;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;

class EstadisticasController extends Controller
{
    public function estad_datos_extra(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $proyecto = $request->buscar;
        $etapa = $request->b_etapa;

        if($etapa == "" && $proyecto!=""){
        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                    DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                    DB::raw('SUM(datos_extra.rang21) as sum21'))
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->where('contratos.status','=',3)
                ->get();
        
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
                                $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratos','creditos.id','=','contratos.id')
                                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                                    DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                                    DB::raw('SUM(datos_extra.rang21) as sum21'))
                                ->where('lotes.etapa_id',$etapa)
                                ->where('contratos.status','=',3)
                                ->get();
                        
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

        

        
        $mascotas[0]->sin_mascotas = $SinMascotas;
        $totalPersonas = $mascotas[0]->sin_mascotas + $mascotas[0]->sumMascota;
        $sinDiscap =$totalPersonas - $discapacitados;
        if($totalPersonas > 0){
                $mascotas[0]->promedioPerros = $mascotas[0]->perros/$totalPersonas;
                $promedioAutos = $mascotas[0]->totalAutos/$totalPersonas;
                $promedioAmasCasa = $mascotas[0]->totalAmaCasa/$totalPersonas;
        }else{
                $mascotas[0]->promedioPerros =0;
                $promedioAutos = 0;
                $promedioAmasCasa = 0;
        }
 

        return ['edades'=>$edades,'mascotas'=>$mascotas, 
                'discap'=>$discapacitados, 
                'sinDiscap'=> $sinDiscap,
                'silla_ruedas'=>$silla_ruedas,
                'promedioAutos'=>$promedioAutos,
                'promedioAmasCasa'=>$promedioAmasCasa];      
    }

    public function resumenProyecto(Request $request){
        if(!$request->ajax())return redirect('/');
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;

        if($etapa!=''){
                $lotes = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('etapa_id','=',$etapa)->count();

                $disponibles = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('etapa_id','=',$etapa)
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
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
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
                                ->where('i.elegido', '=', 1)->paginate(15);

                $disponibles  = $disponibles + $contratos;
                $vendidas = $vendidas - $individualizadas - $indiviDirecto;
                $individualizadas = $individualizadas + $indiviDirecto;
        }
        else{
                $lotes = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->count();

                $disponibles = Lote::where('fraccionamiento_id','=',$proyecto)
                                ->where('contrato','=',0)->count();

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
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
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
                                ->where('i.elegido', '=', 1)->paginate(15);

                $disponibles  = $disponibles + $contratos;
                $vendidas = $vendidas - $individualizadas - $indiviDirecto;
                $individualizadas = $individualizadas + $indiviDirecto;
        }

        

        return[ 'lotes'=>$lotes, 
                'disponibles'=>$disponibles,
                'vendidas'=>$vendidas,
                'individualizadas'=>$individualizadas,
                'sumas'=>$sumas,
                'resContratos'=>$resContratos,
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

    /*public function afluencia(Request $request){
        $mes = $request->mes;
        $ano = $request->ano;
    }*/

}
