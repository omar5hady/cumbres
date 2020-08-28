<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Dep_credito;
use App\Lote;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use App\Historial_descartado;
use App\Modelo;
use App\Solic_detalle;
use App\Contratista;
use App\inst_seleccionada;

use App\Cat_detalle_subconcepto;

use App\Cliente;
use App\Vendedor;

use App\Detalle_previo;
use App\Revision_previa;

class ReportesController extends Controller
{
    public function reporteInventario(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $empresa_const = $request->empresa;
        $empresa_terreno = $request->empresa2;

        if($proyecto == ''){
            $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto',
                        'etapas.id','etapas.fraccionamiento_id')->orderBy('fraccionamientos.nombre','asc')
                ->orderBy('etapas.num_etapa','asc')->get();
        }
        else{
            if($etapa == ''){
                $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto',
                            'etapas.id','etapas.fraccionamiento_id')
                            ->where('etapas.fraccionamiento_id','=',$proyecto)
                            ->orderBy('fraccionamientos.nombre','asc')
                    ->orderBy('etapas.num_etapa','asc')->get();
            }
            else{
                $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto',
                            'etapas.id','etapas.fraccionamiento_id')
                            ->where('etapas.fraccionamiento_id','=',$proyecto)
                            ->where('etapas.id','=',$etapa)->orderBy('fraccionamientos.nombre','asc')
                    ->orderBy('etapas.num_etapa','asc')->get();

            }
        }


        

        foreach($proyectos as $index => $proyecto){
            if($empresa_terreno == '' && $empresa_const == ''){
                $proyecto->totalLotes = Lote::where('fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                    ->where('etapa_id','=',$proyecto->id)->count();

                $firmadasAct = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('lotes.etapa_id','=',$proyecto->id)->distinct()->count();

                $firmadasAnt = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('contratos.fecha','<',$fecha1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('lotes.etapa_id','=',$proyecto->id)->distinct()->count();

                $contadoAct = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                                ->where('lotes.etapa_id','=',$proyecto->id)->distinct()->count();

                $contadoAnt = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('contratos.fecha','<',$fecha1)
                                ->where('lotes.etapa_id','=',$proyecto->id)->distinct()->count();

            }
            else{
                $proyecto->totalLotes = Lote::where('fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                    ->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%')
                                    ->where('lotes.emp_constructora','like','%'.$empresa_const.'%')
                                    ->where('etapa_id','=',$proyecto->id)->count();

                $firmadasAct = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('lotes.etapa_id','=',$proyecto->id)
                                ->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%')
                                    ->where('lotes.emp_constructora','like','%'.$empresa_const.'%')
                                ->distinct()->count();

                $firmadasAnt = Expediente::join('contratos','expedientes.id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('contratos.fecha','<',$fecha1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('lotes.etapa_id','=',$proyecto->id)
                                ->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%')
                                    ->where('lotes.emp_constructora','like','%'.$empresa_const.'%')
                                ->distinct()->count();

                $contadoAct = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                                ->where('lotes.etapa_id','=',$proyecto->id)
                                ->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%')
                                    ->where('lotes.emp_constructora','like','%'.$empresa_const.'%')
                                ->distinct()->count();

                $contadoAnt = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                                ->where('contratos.status','=',3)
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('inst_seleccionadas.elegido','=',1)
                                ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                ->where('contratos.fecha','<',$fecha1)
                                ->where('lotes.etapa_id','=',$proyecto->id)
                                ->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%')
                                    ->where('lotes.emp_constructora','like','%'.$empresa_const.'%')
                                ->distinct()->count();
            }
            

            $proyecto->descAnt = $firmadasAnt + $contadoAnt;
            $proyecto->descAct = $firmadasAct + $contadoAct;

            $proyecto->totalDescarga = $proyecto->descAnt + $proyecto->descAct;

            $proyecto->inventario = $proyecto->totalLotes - $proyecto->totalDescarga;
        }


        return ['resumen'=>$proyectos];
    }

    public function reporteVendedores(Request $request){

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $fecha30 = Carbon::parse($fecha2)->addDays(30)->format('Y-m-d');
        $fecha60 = Carbon::parse($fecha2)->addDays(60)->format('Y-m-d');
        $fecha90 = Carbon::parse($fecha2)->addDays(90)->format('Y-m-d');

        $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                    ->join('users','personal.id','=','users.id')
                    ->select('vendedores.id','personal.nombre','personal.apellidos')
                    ->where('users.condicion','=',1)
                    ->orderBy('personal.nombre','asc')
                    ->get();

        $mostrar = 0;

        if($request->proyecto == ''){
            foreach($vendedores as $index => $vendedor){

                if($fecha1==''){
                    $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7)->count();
                    $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2)->count();
                    $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3)->count();
                    $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4)->count();
                    $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1)->count();
    
                    $vendedor->ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->count();
                    
                    $vendedor->canceladas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',0)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->count();
    
                    $vendedor->nv = Historial_descartado::where('vendedor_id','=',$vendedor->id)
                                    ->count();
                
                }
                else{
                    $mostrar = 1;
                    $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
    
                    $vendedor->ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',3)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->whereBetween('contratos.fecha', [$fecha1,$fecha2])
                        ->count();
    
                    $vendedor->ventas30 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',3)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->whereBetween('contratos.fecha', [$fecha2,$fecha30])
                        ->count();
                    
                    $vendedor->ventas60 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',3)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->whereBetween('contratos.fecha', [$fecha30,$fecha60])
                        ->count();
    
                    $vendedor->ventas90 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',3)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->whereBetween('contratos.fecha', [$fecha60,$fecha90])
                        ->count();
    
    
                    $vendedor->canceladas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',0)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->whereBetween('contratos.fecha', [$fecha1,$fecha90])
                        ->count();
    
                    $vendedor->nv = Historial_descartado::where('vendedor_id','=',$vendedor->id)
                                            ->whereBetween('created_at', [$fecha1,$fecha2])
                                            ->count();
    
                }
    
                if($vendedor->clientes != 0){
                    $vendedor->por_venta=(($vendedor->ventas)/$vendedor->clientes)*100;
                }
                else{
                    $vendedor->por_venta=0;
                }
                ////////////////////////////////////////////////////////////////////////////////////
                if($vendedor->ventas != 0){
                    $vendedor->por_cancel=(($vendedor->canceladas)/$vendedor->ventas)*100;
                }
                else{
                    $vendedor->por_cancel=0;
                }
                ////////////////////////////////////////////////////////////////////////////////////
                if($vendedor->clientes != 0){
                    $vendedor->por_bat=($vendedor->nv/$vendedor->clientes)*100;
                }
                else{;
                    $vendedor->por_bat=0;
                }
                
    
            }
        }
        else{
            foreach($vendedores as $index => $vendedor){

                if($fecha1==''){
                    $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7)->where('proyecto_interes_id','=',$request->proyecto)->count();
                    $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2)->where('proyecto_interes_id','=',$request->proyecto)->count();
                    $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3)->where('proyecto_interes_id','=',$request->proyecto)->count();
                    $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4)->where('proyecto_interes_id','=',$request->proyecto)->count();
                    $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1)->where('proyecto_interes_id','=',$request->proyecto)->count();
    
                    $vendedor->ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->count();
                    
                    $vendedor->canceladas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',0)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->count();
    
                    $vendedor->nv = Historial_descartado::where('vendedor_id','=',$vendedor->id)
                                    ->count();
                
                }
                else{
                    $mostrar = 1;
                    $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
                    $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1)->
                        whereBetween('created_at', [$fecha1, $fecha2])->count();
    
                    $vendedor->ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha1,$fecha2])
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha1,$fecha2])
                        ->count();
    
                    $vendedor->ventas30 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha2,$fecha30])
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha2,$fecha30])
                        ->count();
                    
                    $vendedor->ventas60 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha30,$fecha60])
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha30,$fecha60])
                        ->count();
    
                    $vendedor->ventas90 = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('contratos.status','=',3)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha60,$fecha90])
                        ->orWhere('contratos.status','=',1)
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha60,$fecha90])
                        ->count();
    
    
                    $vendedor->canceladas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->where('creditos.vendedor_id','=',$vendedor->id)
                        ->where('contratos.status','=',0)
                        ->where('clientes.user_alta','=',$vendedor->id)
                        ->whereBetween('clientes.created_at', [$fecha1,$fecha2])
                        ->where('clientes.clasificacion','!=',7)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->whereBetween('contratos.fecha', [$fecha1,$fecha90])
                        ->count();
    
                    $vendedor->nv = Historial_descartado::where('vendedor_id','=',$vendedor->id)
                                            ->whereBetween('created_at', [$fecha1,$fecha2])
                                            ->count();
    
                }
    
                if($vendedor->clientes != 0){
                    $vendedor->por_venta=(($vendedor->ventas + $vendedor->ventas30 + $vendedor->ventas60 + $vendedor->ventas90)/$vendedor->clientes)*100;
                }
                else{
                    $vendedor->por_venta=0;
                }
                ////////////////////////////////////////////////////////////////////////////////////
                if($vendedor->ventas != 0){
                    $vendedor->por_cancel=(($vendedor->canceladas)/($vendedor->ventas + $vendedor->ventas30 + $vendedor->ventas60 + $vendedor->ventas90))*100;
                }
                else{
                    $vendedor->por_cancel=0;
                }
                ////////////////////////////////////////////////////////////////////////////////////
                if($vendedor->clientes != 0){
                    $vendedor->por_bat=($vendedor->nv/$vendedor->clientes)*100;
                }
                else{;
                    $vendedor->por_bat=0;
                }
                
    
            }
        }
        

        

        return ['vendedores' => $vendedores,
            'mostrar' => $mostrar
        ];
    }

    public function reporteLotesVentas(Request $request){
        if(!$request->ajax())return redirect('/');
        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto')
                        ->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9= $total10= $total11 = 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId)->count();
        
        /// CONSULTAS PARA TODAS LAS DISPONIBLES (lotes.contrato = 0)
            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',90)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [1, 90])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','=',0)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

        // CONSULTAS PARA OBTENER LAS COBRADAS    
            $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $indivCredito = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $lote->cobradas = $indivContado + $indivCredito;

        //Proceso vendidas no cobradas
            $procVenNoCobrada1 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.liquidado','=',0)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

                                        
            $procVenNoCobrada2 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->whereNull('expedientes.fecha_firma_esc')
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $procVenNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90)
                                        ->count('contratos.id');

            $procVenNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90)
                                        ->count('contratos.id');


            $lote->procVendidaNoCobrada = $procVenNoCobrada2 + $procVenNoCobrada1  + $procVenNoCobrada3 + $procVenNoCobrada4;

        
            $termVendidaNoCobrada1 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('expedientes.liquidado','=',0)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $termVendidaNoCobrada2 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereNull('expedientes.fecha_firma_esc')
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $termVendidaNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('contratos.status','=',1)
                                        ->count('contratos.id');

            
            $termVendidaNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->count('contratos.id');
            
            $lote->muestraTerminada = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)->count();
        
            $lote->muestraProceso = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->termVendidaNoCobrada = $termVendidaNoCobrada1 + $termVendidaNoCobrada2 + $termVendidaNoCobrada3 + $termVendidaNoCobrada4;

            $lote->terminadaNoCobrada = $lote->terminadaDisponible + $lote->termVendidaNoCobrada;
            $lote->procesoNoCobrada = $lote->procVendidaNoCobrada + $lote->procesoDisponible;

            $total1 += $lote->lotes;
            $total2 += $lote->cobradas;
            $total3 += $lote->terminadaNoCobrada;
            $total4 += $lote->termVendidaNoCobrada;
            $total5 += $lote->terminadaDisponible;
            $total6 += $lote->procesoNoCobrada;
            $total7 += $lote->procVendidaNoCobrada;
            $total8 += $lote->procesoDisponible;
            $total9 += $lote->sinAvanceDisponible;
            $total10 += $lote->muestraTerminada;
            $total11 += $lote->muestraProceso;

        }

        return ['lotes'=>$lotes,
                'total1'=>$total1,        
                'total2'=>$total2,
                'total3'=>$total3,
                'total4'=>$total4,
                'total5'=>$total5,
                'total6'=>$total6,
                'total7'=>$total7,
                'total8'=>$total8,
                'total9'=>$total9,
                'total10'=>$total10,
                'total11'=>$total11
            ];
    }

    public function excelReporteLotesVentas(Request $request){

        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto')
                        ->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9 = $total10 = $total11= 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId)->count();

            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',90)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [1, 90])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','=',0)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

        // COBRADAS    
            $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $indivCredito = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $lote->cobradas = $indivContado + $indivCredito;

        //Proceso vendidas no cobradas
            $procVenNoCobrada1 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.liquidado','=',0)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

                                        
            $procVenNoCobrada2 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.fecha_firma_esc','=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $procVenNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',1)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->count('contratos.id');

            $procVenNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->count('contratos.id');


            $lote->procVendidaNoCobrada = $procVenNoCobrada2 + $procVenNoCobrada1  + $procVenNoCobrada3 + $procVenNoCobrada4;

        
            $termVendidaNoCobrada1 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.liquidado','=',0)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('licencias.avance','>',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');

            $termVendidaNoCobrada2 = Contrato::leftJoin('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',1)
                                        ->where('expedientes.fecha_firma_esc','=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('licencias.avance','>',90)
                                        ->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $termVendidaNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('licencias.avance','>',90)
                                        ->where('contratos.status','=',1)
                                        ->count('contratos.id');

            
            $termVendidaNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('licencias.avance','>',90)
                                        ->count('contratos.id');

            // $termVendidaNoCobrada4 = $termVendidaNoCobrada4 - $termVendidaNoCobrada2 - $termVendidaNoCobrada1;

            // if($termVendidaNoCobrada4 < 0){
            //     $termVendidaNoCobrada4 = 0;

            $lote->muestraTerminada = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)->count();
        
            $lote->muestraProceso = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)->count();
            // }

            $lote->termVendidaNoCobrada = $termVendidaNoCobrada1 + $termVendidaNoCobrada2 + $termVendidaNoCobrada3 + $termVendidaNoCobrada4;

            $lote->terminadaNoCobrada = $lote->terminadaDisponible + $lote->termVendidaNoCobrada;
            $lote->procesoNoCobrada = $lote->procVendidaNoCobrada + $lote->procesoDisponible;

            $total1 += $lote->lotes;
            $total2 += $lote->cobradas;
            $total3 += $lote->terminadaNoCobrada;
            $total4 += $lote->termVendidaNoCobrada;
            $total5 += $lote->terminadaDisponible;
            $total6 += $lote->procesoNoCobrada;
            $total7 += $lote->procVendidaNoCobrada;
            $total8 += $lote->procesoDisponible;
            $total9 += $lote->sinAvanceDisponible;
            $total10 += $lote->muestraTerminada;
            $total11 += $lote->muestraProceso;


        }

        return Excel::create('Reporte', function($excel) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9, $total10, $total11){
            $excel->sheet('Reoorte lotes', function($sheet) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9, $total10, $total11){

                $sheet->row(1, [
                    'Fraccionamiento',
                    'Etapa',
                    'Total Casas o Lotes',
                    'Cobradas',
                    'Terminadas No Cobradas',
                    'Terminadas Vendidas No Cobradas',
                    'Terminadas Disponibles',
                    'En Proceso de Construccion No Cobradas',
                    'En Proceso Vendidas No Cobradas',
                    'En Proceso Disponible',
                    'Disponibles Sin Avance',
                    'Casa muestra terminada',
                    'Casa muestra en proceso'
                ]);
                

                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=3;
                $renglon = 2;

                

                foreach($lotes as $index => $lote) {
                    $cont++;


                    if($lote->lotes != 0){

                        $sheet->row($renglon, [
                            $lote->proyecto, 
                            $lote->num_etapa,
                            $lote->lotes,
                            $lote->cobradas,
                            $lote->terminadaNoCobrada,
                            $lote->termVendidaNoCobrada,
                            $lote->terminadaDisponible,
                            $lote->procesoNoCobrada,
                            $lote->procVendidaNoCobrada,
                            $lote->procesoDisponible,
                            $lote->sinAvanceDisponible,
                            $lote->muestraTerminada,
                            $lote->muestraProceso

                        ]);	
                        $renglon ++;
                    }
                }
                $sheet->row($renglon+2,[
                    '',
                    'Total',
                    $total1,
                    $total2,
                    $total3,
                    $total4,
                    $total5,
                    $total6,
                    $total7,
                    $total8,
                    $total9,
                    $total10,
                    $total11,
                    
                ]);

                $renglon = $renglon + 2;
            
                $num='A1:M' . $renglon;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$renglon.':'.'M'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');
    
                    // Set font size
                    $cells->setFontSize(11);
    
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });

            
        }
        
        )->download('xls');

    }

    public function reporteVentas(Request $request){

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'lotes.firmado',
                                'creditos.costo_descuento', 'creditos.descuento_terreno', 'creditos.costo_alarma',
                                'creditos.costo_cuota_mant', 'creditos.costo_protecciones','contratos.id',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta','contratos.status')
                        
                        ->where('contratos.status','=',3)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])
                        ->orWhere('contratos.status','=',1)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])
                        ->orWhere('contratos.status','=',0)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])
                        ->orderBy('contratos.status','desc')
                        ->orderBy('contratos.fecha','asc')
                        ->get();

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete',
                                'contratos.fecha_status')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',0)
                        ->whereBetween('contratos.fecha_status', [$request->fecha, $request->fecha2])
                        ->orderBy('contratos.fecha_status','asc')
                        ->get();

        
        $contVentas = $ventas->count();
        $contCancelaciones = $cancelaciones->count();
        return[
            'ventas'=>$ventas,
            'contVentas'=>$contVentas,
            'contCancelaciones'=>$contCancelaciones,
            'cancelaciones'=>$cancelaciones,
        ];
    }

    public function reporteVentasExcel(Request $request){

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'creditos.costo_descuento', 'creditos.descuento_terreno', 'creditos.costo_alarma',
                                'creditos.costo_cuota_mant', 'creditos.costo_protecciones','contratos.id',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'contratos.status','lotes.firmado',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',3)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])

                        ->orWhere('contratos.status','=',1)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])

                        ->orWhere('contratos.status','=',0)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])
                        ->orderBy('contratos.status','desc')
                        ->orderBy('contratos.fecha','asc')
                        ->get();

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'lotes.firmado',
                                'contratos.fecha_status')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',0)
                        ->whereBetween('contratos.fecha_status', [$request->fecha, $request->fecha2])
                        ->orderBy('contratos.fecha','asc')
                        ->get();

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($request->fecha);
                        $fecha1 = $fecha1->formatLocalized('%d de %B de %Y');

                        $fecha2 = new Carbon($request->fecha2);
                        $fecha2 = $fecha2->formatLocalized('%d de %B de %Y');

                        $periodo = 'Del '.$fecha1.' al '.$fecha2;

        
        return Excel::create('Reporte de ventas y cancelaciones', function($excel) use ($ventas,$cancelaciones,$periodo){
            $excel->sheet('Ventas', function($sheet) use ($ventas,$periodo){

                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A2', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE VENTAS');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('B4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                
                });

                $sheet->row(4,[
                    '',
                    'Periodo: ',
                    $periodo
                ]);

                $sheet->cell('C4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'K' => '$#,##0.00',
                    'L' => '$#,##0.00',
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                ));

                $sheet->row(8, [
                    'No.',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Cliente',
                    'Fecha de venta',
                    'Crédito',
                    'Institución',
                    'Promoción / Paquete',
                    'Valor de escrituración',
                    'Valor de terreno',
                    'Valor de construccion',
                    'Descuento precio de casa o equipamiento',
                    'Descuento en el terreno',
                    'Costo de Alarma',
                    'Cuota de mantenimiento',
                    'Protecciones'
                ]);
                

                $sheet->cells('A8:T8', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=9;

                

                foreach($ventas as $index => $lote) {
                    $cont++;

                    $paquete = '';

                    $status='';
                    if($lote->status == 0)
                        $status = 'Cancelado';
                    elseif($lote->status == 1 || $lote->status == 3 && $lote->firmado == 0)
                        $status = 'Vendida';
                    elseif($lote->status == 3 && $lote->firmado == 1)
                        $status = 'Individualizada';

                    if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null) 
                        $paquete = '';
                    elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                        $paquete = 'Promo: '.$lote->descripcion_promocion;
                    elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                        $paquete = 'Paquete: '.$lote->descripcion_paquete;
                    else 
                        $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                    $sheet->row($index+9, [
                        $index+1,
                        $lote->proyecto, 
                        $lote->num_etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->nombre.' '.$lote->apellidos,
                        $lote->fecha,
                        $lote->tipo_credito,
                        $lote->institucion,
                        $paquete,
                        $lote->precio_venta,
                        $lote->valor_terreno,
                        $lote->precio_venta - $lote->valor_terreno,
                        $lote->costo_descuento,
                        $lote->descuento_terreno,
                        $lote->costo_alarma,
                        $lote->costo_cuota_mant,
                        $lote->costo_protecciones,
                        $status,
                    ]);	
                }
            
                $num='A8:S' . $cont;
                $sheet->setBorder($num, 'thin');

                
            });

            $excel->sheet('Cancelaciones', function($sheet) use ($cancelaciones,$periodo){

                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A2:L2');
                $sheet->mergeCells('C4:G4');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A2', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE CANCELACIONES');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('B4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                
                });

                $sheet->row(4,[
                    '',
                    'Periodo: ',
                    $periodo
                ]);

                $sheet->cell('C4', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'L' => '$#,##0.00',
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                ));

                $sheet->row(8, [
                    'No.',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Cliente',
                    'Fecha de cancelación',
                    'Fecha de venta',
                    'Crédito',
                    'Institución',
                    'Promoción / Paquete',
                    'Valor de escrituración',
                    'Valor de terreno',
                    'Valor de construccion'
                ]);
                

                $sheet->cells('A8:L8', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $cont=9;

                

                foreach($cancelaciones as $index => $lote) {
                    $cont++;

                    $paquete = '';
                    
                    if($lote->descripcion_promocion == null && $lote->descripcion_paquete == null) 
                        $paquete = '';
                    elseif($lote->descripcion_promocion != null && $lote->descripcion_paquete == null)
                        $paquete = 'Promo: '.$lote->descripcion_promocion;
                    elseif($lote->descripcion_promocion == null && $lote->descripcion_paquete != null)
                        $paquete = 'Paquete: '.$lote->descripcion_paquete;
                    else 
                        $paquete = 'Promo: ' . $lote->descripcion_promocion . ' / Paquete:' . $lote->descripcion_paquete;

                    $sheet->row($index+9, [
                        $index+1,
                        $lote->proyecto, 
                        $lote->num_etapa,
                        $lote->manzana,
                        $lote->num_lote,
                        $lote->nombre.' '.$lote->apellidos,
                        $lote->fecha_status,
                        $lote->fecha,
                        $lote->tipo_credito,
                        $lote->institucion,
                        $paquete,
                        $lote->precio_venta,
                        $lote->valor_terreno,
                        $lote->precio_venta - $lote->valor_terreno
                    ]);	
                }
            
                $num='A8:N' . $cont;
                $sheet->setBorder($num, 'thin');

                
            });
            
        }
        
        )->download('xls');
    }

    public function reporteAcumulado(Request $request){
        $mes = $request->mes;
        $anio = $request->anio;
        $expCreditos = $this->getRepExpedientes($mes, $anio);
        $expContado = $this->getRepExpContado($mes, $anio);
        $sinEntregar = $this->getSinEntregarRep($mes,$anio);
        
        $escrituras = $this->getEscriturasRep($mes, $anio);
        $contadoSinEscrituras = $this->getContadoSinEscrituras($mes, $anio);
        $ingresosCobranza = $this->getIngresosCobranza($mes,$anio);
        
        return ['expCreditos'=>$expCreditos,
                'expContado'=>$expContado,
                'pendientes'=>$sinEntregar,
                'escrituras'=>$escrituras,
                'contadoSinEscrituras'=>$contadoSinEscrituras,
                'ingresosCobranza'=>$ingresosCobranza

            ];

    }

    private function getRepExpedientes($mes, $anio){//$mes, $anio){

        $mes2 = $mes + 1;
        $fecha = $anio.'-'.$mes2.'-01';
        $depositos = Dep_credito::join('inst_seleccionadas as ins','dep_creditos.inst_sel_id','=','ins.id')
                ->join('creditos','ins.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select(//'dep_creditos.inst_sel_id',
                            'contratos.id','p.nombre','p.apellidos',
                            'f.nombre as proyecto','et.num_etapa',
                            'lotes.manzana','lotes.num_lote',
                            DB::raw("SUM(dep_creditos.cant_depo) as totalDep")
                            )
                ->where('dep_creditos.fecha_deposito','<',$fecha)
                ->groupBy('ins.credito_id')
                ->distinct()->get();

        if(sizeOf($depositos)){
            foreach($depositos as $index => $deposito){
                $inst = inst_seleccionada::select('monto_credito','segundo_credito','tipo_credito','institucion')
                        ->where('elegido','=',1)
                        ->where('credito_id','=',$deposito->id)->get();
                
                $act = Dep_credito::join('inst_seleccionadas as ins','dep_creditos.inst_sel_id','=','ins.id')
                        ->join('creditos','ins.credito_id','=','creditos.id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('dep_creditos.fecha_deposito')
                        ->where('ins.credito_id','=',$deposito->id)
                        ->whereYear('dep_creditos.fecha_deposito',$anio)
                        ->whereMonth('dep_creditos.fecha_deposito',$mes)->get();
                
                $deposito->totalCredito = round($inst[0]->monto_credito + $inst[0]->segundo_credito,2);
                $deposito->totalDep = round($deposito->totalDep,2);
                $deposito->tipo_credito = $inst[0]->tipo_credito;
                $deposito->institucion = $inst[0]->institucion;
                $deposito->flag = 0;
                $deposito->mes = 0;
                if($deposito->totalCredito == $deposito->totalDep)
                    $deposito->flag = 1;
                if(sizeOf($act)){
                    $deposito->mes = 1;
                }
            
            }
        }

        return $depositos;
    }

    private function getSinEntregarRep($mes,$anio){
        $sinEntregar = inst_seleccionada::join('creditos','inst_seleccionadas.credito_id','=','creditos.id')
                ->join('contratos','creditos.id','=','contratos.id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select(//'dep_creditos.inst_sel_id',
                            'inst_seleccionadas.id as crd_id',
                            'contratos.id','p.nombre','p.apellidos',
                            'f.nombre as proyecto','et.num_etapa',
                            'inst_seleccionadas.tipo_credito','inst_seleccionadas.institucion',
                            'lotes.manzana','lotes.num_lote'
                            )
                ->where('inst_seleccionadas.tipo','=',0)
                ->where('inst_seleccionadas.elegido','=',1)
                ->where('inst_seleccionadas.status','=',2)
                ->where('expedientes.fecha_firma_esc','!=', NULL)
                ->where('contratos.status','=',3)
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                ->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito')
                ->orWhere('inst_seleccionadas.tipo','=',1)
                ->where('inst_seleccionadas.elegido','=',0)
                ->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito')
                ->where('inst_seleccionadas.status','=',2)
                ->where('expedientes.fecha_firma_esc','!=', NULL)
                ->where('contratos.status','=',3)
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                ->orderBy('contratos.fecha','asc')->get();

        return $sinEntregar;
    }
    
    private function getRepExpContado($mes, $anio){
        $expContado = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.num_lote',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','expedientes.fecha_firma_esc')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','=','Crédito Directo')
                ->whereMonth('expedientes.fecha_firma_esc',$mes)
                ->whereYear('expedientes.fecha_firma_esc',$anio)
                ->orderBy('expedientes.fecha_firma_esc','asc')->get();

        return $expContado;
    }

    private function getEscriturasRep($mes, $anio){
        $escrituras = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.manzana','lotes.num_lote','ins.tipo_credito','ins.institucion',
                        'expedientes.fecha_firma_esc','expedientes.valor_escrituras','expedientes.notaria')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('expedientes.fecha_firma_esc','!=',NULL)
                ->whereMonth('expedientes.fecha_firma_esc',$mes)
                ->whereYear('expedientes.fecha_firma_esc',$anio)
                ->orderBy('expedientes.fecha_firma_esc','desc')->get();
        
        return $escrituras;
    }

    private function getContadoSinEscrituras($mes, $anio){
        $contadoSinEscrituras = Expediente::join('contratos','expedientes.id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('personal as p','creditos.prospecto_id','=','p.id')
            ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
            ->join('etapas as et','lotes.etapa_id','=','et.id')
            ->join('personal as g','expedientes.gestor_id','=','g.id')
            ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                    'lotes.manzana','lotes.num_lote','ins.tipo_credito','ins.institucion',
                    DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor"),'contratos.fecha',
                    'expedientes.fecha_firma_esc','expedientes.valor_escrituras','expedientes.notaria')
            ->where('contratos.status','=',3)
            ->where('contratos.saldo','<=',0)
            ->where('ins.elegido','=',1)
            ->where('expedientes.fecha_firma_esc','=',NULL)
            ->where('ins.tipo_credito','=','Crédito Directo')
            ->orderBy('contratos.fecha')->get();
    
        return $contadoSinEscrituras;
    }

    private function getIngresosCobranza($mes, $anio){
        $ingresosCobranza = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('dep_creditos as dep','ins.id','=','dep.inst_sel_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.manzana','lotes.num_lote','dep.cant_depo','dep.fecha_deposito','dep.banco')
                ->whereMonth('dep.fecha_deposito',$mes)
                ->whereYear('dep.fecha_deposito',$anio)
                ->orderBy('dep.fecha_deposito')->get();
        return $ingresosCobranza;
    }

    public function reporteRecursosPropios(Request $request){
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->select('lotes.id')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orderBy('lotes.id','asc')
                                        ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual){
                array_push($indiv,$individual->id);
            }
                                        
        
        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra');
    
        if($request->proyecto == ''){
            $lotes = $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);
                        
        }
        else{
            if($request->etapa == ''){
                $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto);
            }
            else{
                $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa);
            }
        }
                        
        $lotes2 = $lotes->get();

        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(25);

        

        
        if(sizeOf($lotes)){
            foreach($lotes as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();

                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }
            }
        }

        if(sizeOf($lotes2)){
            $suma1=0;
            $suma2=0;
            $suma3=0;
            foreach($lotes2 as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();
                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }

                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }


        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes,
            'suma1' => $suma1,
            'suma2' => $suma2,
            'suma3' => $suma3
        ];
    }

    public function excelRecursosPropios(Request $request){
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->select('lotes.id')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orderBy('lotes.id','asc')
                                        ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual){
                array_push($indiv,$individual->id);
            }
                                        
        
        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra');
    
        if($request->proyecto == ''){
            $lotes = $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);
                        
        }
        else{
            if($request->etapa == ''){
                $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto);
            }
            else{
                $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa);
            }
        }
                        
        // $lotes2 = $lotes->get();

        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->get();

        

        
        if(sizeOf($lotes)){
            $suma1=0;
            $suma2=0;
            $suma3=0;
            foreach($lotes as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();
                                
                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }

                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }

       
        return Excel::create('Reporte', function($excel) use ($lotes, $suma1, $suma2, $suma3){
            $excel->sheet('Reoorte recursos propios', function($sheet) use ($lotes, $suma1, $suma2, $suma3){

                $sheet->row(1, ['',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Estatus',
                    'Avance',
                    'Valor de Venta',
                    'Cobrado',
                    'Por Cobrar',
                    'Credito Puente'
                ]);
                

                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));

                $cont=3;
                $renglon = 2;

                $i = 1;

                foreach($lotes as $index => $lote) {
                    $cont++;

                    if($lote->status == 0){
                        $status = 'Disponible';
                    }
                    else{
                        $status = 'Vendida';
                    }


                    

                        $sheet->row($renglon, [
                            $i,
                            $lote->proyecto, 
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $status,
                            $lote->avance.'%',
                            $lote->valor_venta,
                            $lote->cobrado,
                            $lote->porCobrar,
                            $lote->credito_puente,

                        ]);	
                        $renglon ++;
                        $i++;
                    
                }
                $sheet->row($renglon+1,[
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Total',
                    $suma1,
                    $suma2,
                    $suma3,
                   ''
                    
                ]);

                $renglon = $renglon + 1;
            
                $num='A1:K' . $renglon;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$renglon.':'.'K'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');
    
                    // Set font size
                    $cells->setFontSize(11);
    
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });

            
        }
        
        )->download('xls');

       
    }

    public function reporteCasasCreditoPuente(Request $request){
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->select('lotes.id')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orderBy('lotes.id','asc')
                                        ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual){
                array_push($indiv,$individual->id);
            }
                                        
        
        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra');
    
        if($request->proyecto == ''){
            $lotes = $lotes->where('lotes.credito_puente','NOT LIKE','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
                        
                       
                        
        }
        else{
            if($request->etapa == ''){
                $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
            }
            else{
                $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
            }
        }
                        
        $lotes2 = $lotes->get();

        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->paginate(25);

        

        
        if(sizeOf($lotes)){
            foreach($lotes as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();

                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }
            }
        }

        if(sizeOf($lotes2)){
            $suma1=0;
            $suma2=0;
            $suma3=0;
            foreach($lotes2 as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();
                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }

                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }


        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes,
            'suma1' => $suma1,
            'suma2' => $suma2,
            'suma3' => $suma3
        ];
    }

    public function excelCasasCreditoPuente(Request $request){
        $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                        ->join('creditos','contratos.id', '=', 'creditos.id')
                                        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->select('lotes.id')
                                        ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.liquidado','=',1)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orWhere('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                        ->where('contratos.status','=',3)
                                        ->where('expedientes.fecha_firma_esc','!=',NULL)
                                        ->where('inst_seleccionadas.elegido', '=', '1')
                                        
                                        ->orderBy('lotes.id','asc')
                                        ->distinct('contratos.id')->get();
        $indiv = [];

        if(sizeof($indivContado))
            foreach($indivContado as $index => $individual){
                array_push($indiv,$individual->id);
            }
                                        
        
        $lotes = Lote::join('fraccionamientos','fraccionamientos.id','=','lotes.fraccionamiento_id')
                        ->join('etapas','etapas.id','=','lotes.etapa_id')
                        ->join('licencias','licencias.id','=','lotes.id')
                        ->select('etapas.num_etapa','lotes.manzana','fraccionamientos.nombre as proyecto',
                                'licencias.id', 'lotes.credito_puente','licencias.avance', 'lotes.num_lote',
                                'lotes.precio_base', 'lotes.excedente_terreno','lotes.sobreprecio',
                                'lotes.ajuste','lotes.obra_extra');
    
        if($request->proyecto == ''){
            $lotes = $lotes->where('lotes.credito_puente','NOT LIKE','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
                        
                        
                        
        }
        else{
            if($request->etapa == ''){
                $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
            }
            else{
                $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.fraccionamiento_id','=',$request->proyecto)
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
            }
        }
                        
        // $lotes2 = $lotes->get();

        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->get();

        

        
        if(sizeOf($lotes)){
            $suma1=0;
            $suma2=0;
            $suma3=0;
            foreach($lotes as $index => $lote){

                $lote->valor_venta = $lote->precio_base + $lote->excedente_terreno + $lote->sobreprecio +
                                        $lote->obra_extra - $lote->ajuste;
                $lote->porCobrar = 0;
                $lote->status = 0;
                $lote->cobrado = 0;

                $contratos = Contrato::join('creditos','creditos.id','=','contratos.id')
                        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                        ->select('creditos.precio_venta','contratos.id')
                        ->where('contratos.status', '=', 3)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->orWhere('contratos.status','=', 1)
                        ->where('i.elegido','=',1)
                        ->where('creditos.lote_id','=',$lote->id)
                        ->get();

                if(sizeOf($contratos) > 0){
                    $lote->valor_venta = $contratos[0]->precio_venta;
                    $lote->status = 1;
                    $pagos = Pago_contrato::select(
                        DB::raw("SUM(monto_pago) as sumMontoPago"), 
                        DB::raw("SUM(restante) as sumRestante"))
                                ->where('contrato_id','=',$contratos[0]->id)
                                ->get();
                                
                    $lote->pagos = $pagos;
                    $lote->cobrado = $pagos[0]->sumMontoPago - $pagos[0]->sumRestante;
                    $lote->porCobrar =$lote->valor_venta - $lote->cobrado;
                }

                $suma1 += $lote->valor_venta;
                $suma2 += $lote->cobrado;
                $suma3 += $lote->porCobrar;
            }
        }

       
        return Excel::create('Reporte', function($excel) use ($lotes, $suma1, $suma2, $suma3){
            $excel->sheet('Casas credito puente', function($sheet) use ($lotes, $suma1, $suma2, $suma3){

                $sheet->row(1, ['',
                    'Fraccionamiento',
                    'Etapa',
                    'Manzana',
                    'Lote',
                    'Estatus',
                    'Avance',
                    'Valor de Venta',
                    'Cobrado',
                    'Por Cobrar',
                    'Credito Puente'
                ]);
                

                $sheet->cells('A1:K1', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->setColumnFormat(array(
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'J' => '$#,##0.00',
                ));

                $cont=3;
                $renglon = 2;

                $i = 1;

                foreach($lotes as $index => $lote) {
                    $cont++;

                    if($lote->status == 0){
                        $status = 'Disponible';
                    }
                    else{
                        $status = 'Vendida';
                    }


                    

                        $sheet->row($renglon, [
                            $i,
                            $lote->proyecto, 
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $status,
                            $lote->avance.'%',
                            $lote->valor_venta,
                            $lote->cobrado,
                            $lote->porCobrar,
                            $lote->credito_puente,

                        ]);	
                        $renglon ++;
                        $i++;
                    
                }
                $sheet->row($renglon+1,[
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Total',
                    $suma1,
                    $suma2,
                    $suma3,
                   ''
                    
                ]);

                $renglon = $renglon + 1;
            
                $num='A1:K' . $renglon;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$renglon.':'.'K'.$renglon, function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');
    
                    // Set font size
                    $cells->setFontSize(11);
    
                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                });
            });

            
        }
        
        )->download('xls');

       
    }

    public function reporteModelos(Request $request){

        $fraccionamiento = $request->fraccionamiento;
        $etapa = $request->etapa;
        $diff_in_months = 0;
        
        $modelos = Modelo::select('nombre')
        ->where('nombre','!=','Por Asignar');
        if($fraccionamiento != ''){
           $modelos =  $modelos->where('fraccionamiento_id','=',$fraccionamiento);
        }
        
        $modelos = $modelos->orderBy('nombre','asc')->distinct()->get();

        if($fraccionamiento != ''){
            $vendidasFin = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select('contratos.fecha')
                                ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                                ->where('contratos.status','=',3)
                                ->where('lotes.habilitado','=',1)
                                ->orderBy('contratos.fecha','desc')
                                ->get();

                $fracc = Fraccionamiento::select('fecha_ini_venta')->where('id','=',$fraccionamiento)->get();
                $fecha = $fracc[0]->fecha_ini_venta;

                if($fecha){
                        $to = Carbon::createFromFormat('Y-m-d', $vendidasFin[0]->fecha);
                        $from = Carbon::createFromFormat('Y-m-d', $fecha);
                        $diff_in_months = $to->diffInMonths($from);
                }
        }

        if($etapa != ''){
            $vendidasFin = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->select('contratos.fecha')
                                ->where('lotes.fraccionamiento_id','=',$fraccionamiento)
                                ->where('lotes.etapa_id','=',$etapa)
                                ->where('lotes.habilitado','=',1)
                                ->where('contratos.status','=',3)
                                ->orderBy('contratos.fecha','desc')
                                ->get();

                $fracc = Etapa::select('fecha_ini_venta')->where('id','=',$etapa)->where('fraccionamiento_id','=',$fraccionamiento)->get();
                $fecha = $fracc[0]->fecha_ini_venta;
                if($fecha){
                        $to = Carbon::createFromFormat('Y-m-d', $vendidasFin[0]->fecha);
                        $from = Carbon::createFromFormat('Y-m-d', $fecha);
                        $diff_in_months = $to->diffInMonths($from);
                }
        }
        

        foreach($modelos as $index => $modelo){
            $modelo->total = 0;

            $lotes = Lote::join('modelos','lotes.modelo_id','=','modelos.id')->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
            if($fraccionamiento != '')
                $lotes = $lotes->where('lotes.fraccionamiento_id','=',$fraccionamiento);
            if($etapa != '')
                $lotes = $lotes->where('lotes.etapa_id','=',$etapa);
            $lotes = $lotes->count();
            $modelo->total = $lotes;

            $indivContado   =   Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.liquidado','=',1)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);
                                if($fraccionamiento != '')
                                    $indivContado=$indivContado->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                if($etapa != '')
                                    $indivContado=$indivContado->where('lotes.etapa_id','=',$etapa);
                                $indivContado = $indivContado->distinct('contratos.id')
                                                            ->count('contratos.id');
            
            $indivCredito = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);
                                if($fraccionamiento != '')
                                    $indivCredito=$indivCredito->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                if($etapa != '')
                                    $indivCredito=$indivCredito->where('lotes.etapa_id','=',$etapa);
                                $indivCredito=$indivCredito->distinct('contratos.id')
                                ->count('contratos.id');

            $contratos = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->where('contratos.status','=',3)
                            ->where('modelos.nombre','=',$modelo->nombre);
                            if($fraccionamiento != '')
                                $contratos=$contratos->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                            if($etapa != '')
                                $contratos=$contratos->where('lotes.etapa_id','=',$etapa);
                            $contratos=$contratos->orWhere('contratos.status','=',1)
                            ->where('modelos.nombre','=',$modelo->nombre);
                            if($fraccionamiento != '')
                                $contratos=$contratos->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                            if($etapa != '')
                                $contratos=$contratos->where('lotes.etapa_id','=',$etapa);
                           
                            $contratos=$contratos->distinct('contratos.id')
                            ->count('contratos.id');

            $modelo->indiv = $indivContado + $indivCredito;

            $modelo->vendida = $contratos - ($indivContado + $indivCredito);

            $modelo->total_vendidas = $modelo->indiv + $modelo->vendida;

            $modelo->disponible = $modelo->total - ($modelo->indiv + $modelo->vendida );

            
        }

        return [
            'modelos'=>$modelos, 
            'diferencia'=>$diff_in_months,
            ];

    }


    public function reporteDetalles(Request $request){
        $contratistas = Contratista::select('id', 'nombre')->orderBy('nombre','asc')->get();


        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                    ->join('descripcion_detalles as det','solic_detalles.id','=','det.solicitud_id')
                                    ->join('cat_detalles as d','det.detalle_id','=','d.id')
                                    ->join('cat_detalles_subconceptos as sub','d.id_sub','=','sub.id')
                                    ->join('cat_detalles_generales as gral','sub.id_gral','=','gral.id')
                                    ->join('contratistas as c','solic_detalles.contratista_id','=','c.id')
                                    ->join('creditos as cr','contratos.id','=','cr.id')
                                    ->join('lotes','cr.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id');


        $resumen = $solicitudes->select('solic_detalles.cliente','solic_detalles.status','lotes.num_lote',
                                            'fraccionamientos.nombre as proyecto','det.id', 'solic_detalles.created_at',
                                            'etapas.num_etapa','lotes.manzana','d.detalle','sub.subconcepto','gral.general',
                                            DB::raw("CONCAT(gral.general,'/ ',sub.subconcepto) AS detalles"),
                                            'c.nombre','solic_detalles.contratista_id');

                if($request->contratista != ''){
                    $resumen = $resumen->where('c.id','=',$request->contratista);
                }

                if($request->proyecto != ''){
                    $resumen = $resumen->where('fraccionamientos.id','=',$request->proyecto);
                    if($request->etapa != '')
                        $resumen = $resumen->where('etapas.id','=',$request->etapa);
                }
                                    
        $resumen1 = $resumen->orderBy('solic_detalles.status','desc')
                                    ->get();

        $detalles = Cat_detalle_subconcepto::join('cat_detalles_generales as gral','cat_detalles_subconceptos.id_gral','=','gral.id')
                                ->select('cat_detalles_subconceptos.subconcepto','gral.general','cat_detalles_subconceptos.id',
                                        DB::raw("CONCAT(gral.general,'/ ',cat_detalles_subconceptos.subconcepto) AS detalles"))
                                ->orderBy('gral.general','desc')
                                ->orderBy('cat_detalles_subconceptos.subconcepto','desc')
                                ->get();


        if(sizeOf($resumen1))
            foreach($contratistas as $det => $contratista){
                $contratista->cont=0;
                // $contratista->detalle=[];
                foreach($resumen1 as $index => $detalle){
                    if($detalle->contratista_id == $contratista->id){
                        $contratista->cont++;
                    }
                }
            }

        if(sizeOf($resumen1))
            foreach($detalles as $det => $detalle){
                $detalle->cont=0;
                // $contratista->detalle=[];
                foreach($resumen1 as $index => $res){
                    if($res->detalles == $detalle->detalles){
                        $detalle->cont++;
                    }
                }
            }

            $resumen = $resumen->orderBy('solic_detalles.status','desc')
                                    ->paginate();
            
        
        return ['contratistas'=>$contratistas,
                'resumen'=>$resumen,
                'pagination' => [
                    'total'         => $resumen->total(),
                    'current_page'  => $resumen->currentPage(),
                    'per_page'      => $resumen->perPage(),
                    'last_page'     => $resumen->lastPage(),
                    'from'          => $resumen->firstItem(),
                    'to'            => $resumen->lastItem(),
                ],
                'detalles'=>$detalles];

    }

    public function reporteDetallesExcel(Request $request){
        $contratistas = Contratista::select('id', 'nombre')->orderBy('nombre','asc')->get();


        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                    ->join('descripcion_detalles as det','solic_detalles.id','=','det.solicitud_id')
                                    ->join('cat_detalles as d','det.detalle_id','=','d.id')
                                    ->join('cat_detalles_subconceptos as sub','d.id_sub','=','sub.id')
                                    ->join('cat_detalles_generales as gral','sub.id_gral','=','gral.id')
                                    ->join('contratistas as c','solic_detalles.contratista_id','=','c.id')
                                    ->join('creditos as cr','contratos.id','=','cr.id')
                                    ->join('lotes','cr.lote_id','=','lotes.id')
                                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id');


        $resumen = $solicitudes->select('solic_detalles.cliente','solic_detalles.status','lotes.num_lote',
                                            'det.fecha_concluido','det.revisado',
                                            'fraccionamientos.nombre as proyecto','det.id', 'solic_detalles.created_at',
                                            'etapas.num_etapa','lotes.manzana','d.detalle','sub.subconcepto','gral.general',
                                            DB::raw("CONCAT(gral.general,'/ ',sub.subconcepto) AS detalles"),
                                            'c.nombre','solic_detalles.contratista_id');

                if($request->contratista != ''){
                    $resumen = $resumen->where('c.id','=',$request->contratista);
                }

                if($request->proyecto != ''){
                    $resumen = $resumen->where('fraccionamientos.id','=',$request->proyecto);
                    if($request->etapa != '')
                        $resumen = $resumen->where('etapas.id','=',$request->etapa);
                }
                                    
        $resumen = $resumen->orderBy('solic_detalles.status','desc')
                                    ->get();

        $detalles = Cat_detalle_subconcepto::join('cat_detalles_generales as gral','cat_detalles_subconceptos.id_gral','=','gral.id')
                                ->select('cat_detalles_subconceptos.subconcepto','gral.general','cat_detalles_subconceptos.id',
                                        DB::raw("CONCAT(gral.general,'/ ',cat_detalles_subconceptos.subconcepto) AS detalles"))
                                ->orderBy('gral.general','desc')
                                ->orderBy('cat_detalles_subconceptos.subconcepto','desc')
                                ->get();


        if(sizeOf($resumen))
            foreach($contratistas as $det => $contratista){
                $contratista->cont=0;
                // $contratista->detalle=[];
                foreach($resumen as $index => $detalle){
                    if($detalle->contratista_id == $contratista->id){
                        $contratista->cont++;
                    }
                }
            }

        if(sizeOf($resumen))
            foreach($detalles as $det => $detalle){
                $detalle->cont=0;
                // $contratista->detalle=[];
                foreach($resumen as $index => $res){
                    if($res->detalles == $detalle->detalles){
                        $detalle->cont++;
                    }
                }
            }

        
        


                return Excel::create('Reporte de detalles', function($excel) use ($contratistas,$resumen,$detalles){
                    $excel->sheet('Resumen', function($sheet) use ($resumen){
        
                        $sheet->mergeCells('A1:K1');
                        $sheet->mergeCells('A2:K2');
                        $sheet->mergeCells('C4:G4');
        
                        $sheet->cell('A1', function($cell) {
        
                            // manipulate the cell
                            $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(14);
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        
                        });
                        $sheet->cell('A2', function($cell) {
        
                            // manipulate the cell
                            $cell->setValue(  'REPORTE DE DETALLES');
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(14);
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        
                        });
        
                        $sheet->cell('B4', function($cell) {
        
                            // manipulate the cell
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(12);
                            $cell->setAlignment('center');
                        
                        });
        

        
                        $sheet->cell('C4', function($cell) {
        
                            // manipulate the cell
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(12);
                            $cell->setAlignment('center');
                        });
        
                        $sheet->row(5, [
                            'No.',
                            'Fraccionamiento',
                            'Etapa',
                            'Manzana',
                            'Lote',
                            'Cliente',
                            'Fecha de solicitud',
                            'Descripción del detalle',
                            'Contratista',
                            'Status'
                        ]);
                        
        
                        $sheet->cells('A5:J5', function ($cells) {
                            // Set font family
                            $cells->setFontFamily('Calibri');
        
                            // Set font size
                            $cells->setFontSize(12);
        
                            // Set font weight to bold
                            $cells->setFontWeight('bold');
                            $cells->setAlignment('center');
                        });
        
                        $cont=6;
        
                        
        
                        foreach($resumen as $index => $lote) {
                            $cont++;
        
                            // $fecha = new Carbon($lote->created_at);
                            // $lote->created_at = $fecha->formatLocalized('%d de %B de %Y');
        
                            $status='';
                            if($lote->fecha_concluido == NULL)
                                $status = 'Pendiente';
                            elseif($lote->fecha_concluido != NULL && $lote->revisado == 0)
                                $status = 'Sin revisar';
                            elseif($lote->fecha_concluido != NULL && $lote->revisado == 1)
                                $status = 'Rechazado';
                            elseif($lote->fecha_concluido != NULL && $lote->revisado == 2)
                                $status = 'Concluido';
        
                            $sheet->row($index+6, [
                                $index+1,
                                $lote->proyecto, 
                                $lote->num_etapa,
                                $lote->manzana,
                                $lote->num_lote,
                                $lote->cliente,
                                $lote->created_at,
                                $lote->detalles.'/ '.$lote->detalle,
                                $lote->nombre,
                                $status,
                            ]);	
                        }
                    
                        $num='A5:J' . $cont;
                        $sheet->setBorder($num, 'thin');
        
                        
                    });

                ////////////////////////////////////////////////////////////////////////////////////////
        
                    $excel->sheet('Detalles', function($sheet) use ($detalles, $contratistas){
        
                       
        
                        $sheet->row(1, [
                            'Detalle',
                            '',

                            '',
                            'Contratista',
                            ''
                        ]);
                        
        
                        $sheet->cells('A1:E1', function ($cells) {
                            // Set font family
                            $cells->setFontFamily('Calibri');
        
                            // Set font size
                            $cells->setFontSize(12);
        
                            // Set font weight to bold
                            $cells->setFontWeight('bold');
                            $cells->setAlignment('center');
                        });
        
                        $cont=2;
                        $cont1=2;
        
                        
        
                        foreach($detalles as $index => $lote) {
                            
                            if($lote->cont!=0){
                                
                            
                                $sheet->row($cont, [
                                    $lote->detalles, 
                                    $lote->cont,
                                    
                                ]);	
                                $cont++;
                            }
                        }
                    
                        $num='A1:B' . $cont;
                        $sheet->setBorder($num, 'thin');

                        foreach($contratistas as $index => $lote) {
                            
                            if($lote->cont!=0){
                                
                                $letra1 = 'D'.$cont1;
                                $letra2 = 'E'.$cont1;
                                $nombre = $lote->nombre;

                                $sheet->setCellValue($letra1, $nombre);
                                $sheet->setCellValue($letra2, $lote->cont);
                                $cont1++;
                            }
                        }
                    
                        $num='D1:E' . $cont1;
                        $sheet->setBorder($num, 'thin');
        
                        
                    });
                    
                }
                
                )->download('xls');    

    }
    
}
