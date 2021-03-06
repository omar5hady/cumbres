<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use File;
use Excel;
use Auth;
use Carbon\Carbon;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Dep_credito;
use App\Lote;
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

        
        $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
            ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto','etapas.id','etapas.fraccionamiento_id');
        

        if($proyecto != '')
            $proyectos = $proyectos->where('etapas.fraccionamiento_id','=',$proyecto);
        if($etapa != '')
            $proyectos = $proyectos->where('etapas.id','=',$etapa);

        $proyectos = $proyectos->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')->get();

        foreach($proyectos as $index => $proyecto){
            
            $proyecto->totalLotes = Lote::where('fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')
                    $proyecto->totalLotes = $proyecto->totalLotes->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')
                    $proyecto->totalLotes = $proyecto->totalLotes->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
            
                $proyecto->totalLotes = $proyecto->totalLotes->count();

            $firmadasAct = Expediente::join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('lotes.etapa_id','=',$proyecto->id)
                    ->whereBetween('contratos.fecha', [$fecha1, $fecha2]);
                if($empresa_terreno != '')
                    $firmadasAct = $firmadasAct->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')
                    $firmadasAct = $firmadasAct->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $firmadasAct = $firmadasAct->distinct()->count();

            $firmadasAnt = Expediente::join('contratos','expedientes.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('expedientes.fecha_firma_esc','!=',NULL)
                    ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('contratos.fecha','<',$fecha1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('lotes.etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')
                    $firmadasAnt = $firmadasAnt->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')
                    $firmadasAnt = $firmadasAnt->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $firmadasAnt = $firmadasAnt->distinct()->count();

            $contadoAct = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('contratos.status','=',3)
                    ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->whereBetween('contratos.fecha', [$fecha1, $fecha2])
                    ->where('lotes.etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')
                    $contadoAct = $contadoAct->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_const != '')
                    $contadoAct = $contadoAct->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                $contadoAct = $contadoAct->distinct()->count();

            $contadoAnt = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')->select('expedientes.id')
                    ->where('contratos.status','=',3)
                    ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                    ->where('inst_seleccionadas.elegido','=',1)
                    ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                    ->where('contratos.fecha','<',$fecha1)
                    ->where('lotes.etapa_id','=',$proyecto->id);
                if($empresa_terreno != '')
                    $contadoAnt = $contadoAnt->where('lotes.emp_terreno','like','%'.$empresa_terreno.'%');
                if($empresa_terreno != '')
                    $contadoAnt = $contadoAnt->where('lotes.emp_constructora','like','%'.$empresa_const.'%');
                    $contadoAnt = $contadoAnt->distinct()->count();
            
            

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

        $empresa = $request->emp_constructora;
        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto');
        $lotes = $lotes->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9= $total10= $total11 = 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId);
            
            if($empresa != '')
                $lote->lotes = $lote->lotes->where('lotes.emp_constructora','=', $empresa);
                    
                                
            $lote->lotes = $lote->lotes->count();
        
        /// CONSULTAS PARA TODAS LAS DISPONIBLES (lotes.contrato = 0)
            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',90)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->terminadaDisponible = $lote->terminadaDisponible->where('lotes.emp_constructora','=', $empresa);
                    
            $lote->terminadaDisponible = $lote->terminadaDisponible->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [1, 90])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);
                                
                if($empresa != '')
                    $lote->procesoDisponible = $lote->procesoDisponible->where('lotes.emp_constructora','=', $empresa);
                                
            $lote->procesoDisponible = $lote->procesoDisponible->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','=',0)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->where('lotes.emp_constructora','=', $empresa);
                                
            $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->count();

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
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $indivContado = $indivContado->where('lotes.emp_constructora','=', $empresa);

            $indivContado = $indivContado->distinct('contratos.id')
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
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $indivCredito = $indivCredito->where('lotes.emp_constructora','=', $empresa);

            $indivCredito = $indivCredito->distinct('contratos.id')
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
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada1 = $procVenNoCobrada1->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada1 = $procVenNoCobrada1->distinct('contratos.id')
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
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada2 = $procVenNoCobrada2->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada2 = $procVenNoCobrada2->distinct('contratos.id')
                                        ->count('contratos.id');

            $procVenNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada3 = $procVenNoCobrada3->where('lotes.emp_constructora','=', $empresa);
                                        
            $procVenNoCobrada3 = $procVenNoCobrada3->count('contratos.id');

            $procVenNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada4 = $procVenNoCobrada4->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada4 = $procVenNoCobrada4->count('contratos.id');


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
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada1 = $termVendidaNoCobrada1->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada1 = $termVendidaNoCobrada1->distinct('contratos.id')
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
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada2 = $termVendidaNoCobrada2->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada2 = $termVendidaNoCobrada2->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $termVendidaNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('contratos.status','=',1);

                if($empresa != '')
                    $termVendidaNoCobrada3 = $termVendidaNoCobrada3->where('lotes.emp_constructora','=', $empresa);
                                        
            $termVendidaNoCobrada3 = $termVendidaNoCobrada3->count('contratos.id');

            
            $termVendidaNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada4 = $termVendidaNoCobrada4->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada4 = $termVendidaNoCobrada4->count('contratos.id');
            
            $lote->muestraTerminada = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->muestraTerminada = $lote->muestraTerminada->where('lotes.emp_constructora','=', $empresa);
                                        
            $lote->muestraTerminada = $lote->muestraTerminada->count();
        
            $lote->muestraProceso = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                                        
                if($empresa != '')
                    $lote->muestraProceso = $lote->muestraProceso->where('lotes.emp_constructora','=', $empresa);

            $lote->muestraProceso = $lote->muestraProceso->count();

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

        $empresa = $request->emp_constructora;

        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto')
                        ->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9 = $total10 = $total11= 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId);
            
            if($empresa != '')
                $lote->lotes = $lote->lotes->where('lotes.emp_constructora','=', $empresa);
                    
                                
            $lote->lotes = $lote->lotes->count();
        
        /// CONSULTAS PARA TODAS LAS DISPONIBLES (lotes.contrato = 0)
            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',90)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->terminadaDisponible = $lote->terminadaDisponible->where('lotes.emp_constructora','=', $empresa);
                    
            $lote->terminadaDisponible = $lote->terminadaDisponible->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [1, 90])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);
                                
                if($empresa != '')
                    $lote->procesoDisponible = $lote->procesoDisponible->where('lotes.emp_constructora','=', $empresa);
                                
            $lote->procesoDisponible = $lote->procesoDisponible->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','=',0)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.casa_muestra','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->where('lotes.emp_constructora','=', $empresa);
                                
            $lote->sinAvanceDisponible = $lote->sinAvanceDisponible->count();

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
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $indivContado = $indivContado->where('lotes.emp_constructora','=', $empresa);

            $indivContado = $indivContado->distinct('contratos.id')
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
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $indivCredito = $indivCredito->where('lotes.emp_constructora','=', $empresa);

            $indivCredito = $indivCredito->distinct('contratos.id')
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
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada1 = $procVenNoCobrada1->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada1 = $procVenNoCobrada1->distinct('contratos.id')
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
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada2 = $procVenNoCobrada2->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada2 = $procVenNoCobrada2->distinct('contratos.id')
                                        ->count('contratos.id');

            $procVenNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',1)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada3 = $procVenNoCobrada3->where('lotes.emp_constructora','=', $empresa);
                                        
            $procVenNoCobrada3 = $procVenNoCobrada3->count('contratos.id');

            $procVenNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','<=',90);

                if($empresa != '')
                    $procVenNoCobrada4 = $procVenNoCobrada4->where('lotes.emp_constructora','=', $empresa);

            $procVenNoCobrada4 = $procVenNoCobrada4->count('contratos.id');


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
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada1 = $termVendidaNoCobrada1->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada1 = $termVendidaNoCobrada1->distinct('contratos.id')
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
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada2 = $termVendidaNoCobrada2->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada2 = $termVendidaNoCobrada2->distinct('contratos.id')
                                        ->count('contratos.id');
                                        
            $termVendidaNoCobrada3 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',0)
                                        ->where('contratos.status','=',1);

                if($empresa != '')
                    $termVendidaNoCobrada3 = $termVendidaNoCobrada3->where('lotes.emp_constructora','=', $empresa);
                                        
            $termVendidaNoCobrada3 = $termVendidaNoCobrada3->count('contratos.id');

            
            $termVendidaNoCobrada4 = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                        
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('licencias','lotes.id','=','licencias.id')
                                        ->where('contratos.status','=',3)
                                        ->where('contratos.integracion','=',0)
                                        ->where('lotes.casa_muestra','=',0)
                                        
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId)
                                        ->where('licencias.avance','>',90);

                if($empresa != '')
                    $termVendidaNoCobrada4 = $termVendidaNoCobrada4->where('lotes.emp_constructora','=', $empresa);

            $termVendidaNoCobrada4 = $termVendidaNoCobrada4->count('contratos.id');
            
            $lote->muestraTerminada = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->where('licencias.avance','>',90)
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);

                if($empresa != '')
                    $lote->muestraTerminada = $lote->muestraTerminada->where('lotes.emp_constructora','=', $empresa);
                                        
            $lote->muestraTerminada = $lote->muestraTerminada->count();
        
            $lote->muestraProceso = Lote::join('licencias','lotes.id','=','licencias.id')
                                        ->whereBetween('licencias.avance', [0, 90])
                                        ->where('lotes.casa_muestra','=',1)
                                        ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                        ->where('lotes.etapa_id','=',$lote->etapaId);
                                        
                if($empresa != '')
                    $lote->muestraProceso = $lote->muestraProceso->where('lotes.emp_constructora','=', $empresa);

            $lote->muestraProceso = $lote->muestraProceso->count();

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

        $empresa = $request->empresa;
        $empresa2 = $request->empresa2;
        $publicidad = $request->publicidad;

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'modelos.nombre as modelo',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'lotes.firmado',
                                'creditos.costo_descuento', 'creditos.descuento_terreno', 'creditos.costo_alarma',
                                'creditos.costo_cuota_mant', 'creditos.costo_protecciones','contratos.id',
                                'medios_publicitarios.nombre as publicidad','contratos.publicidad_id',
                                'contratos.avance_lote', 'contratos.motivo_cancel',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta','contratos.status')
                        
                        ->where('contratos.status','=',3)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);
                        
                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($empresa2 != '')
                            $ventas = $ventas->where('lotes.emp_terreno','=', $empresa2);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);

                        $ventas = $ventas->orWhere('contratos.status','=',1)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);


                        $ventas = $ventas->orWhere('contratos.status','=',0)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);

                        $ventas = $ventas->orderBy('contratos.status','desc')
                        ->orderBy('contratos.fecha','asc')
                        ->get();

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
                                'contratos.avance_lote', 'modelos.nombre as modelo',
                                'medios_publicitarios.nombre as publicidad', 'contratos.publicidad_id',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete','contratos.motivo_cancel',
                                'contratos.fecha_status')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',0)
                        ->whereBetween('contratos.fecha_status', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $cancelaciones = $cancelaciones->where('lotes.emp_constructora','=', $empresa);

                        if($empresa2 != '')
                            $cancelaciones = $cancelaciones->where('lotes.emp_terreno','=', $empresa2);

                        if($publicidad != '')
                            $cancelaciones = $cancelaciones->where('contratos.publicidad_id','=', $publicidad);

                        $cancelaciones = $cancelaciones->orderBy('contratos.fecha_status','asc')
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

        $empresa = $request->empresa;
        $empresa2= $request->empresa2;
        $publicidad = $request->publicidad;

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'modelos.nombre as modelo',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete', 'lotes.firmado',
                                'creditos.costo_descuento', 'creditos.descuento_terreno', 'creditos.costo_alarma',
                                'creditos.costo_cuota_mant', 'creditos.costo_protecciones','contratos.id',
                                'medios_publicitarios.nombre as publicidad','contratos.publicidad_id',
                                'contratos.avance_lote', 'contratos.motivo_cancel',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta','contratos.status')
                        
                        ->where('contratos.status','=',3)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);
                        
                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($empresa2 != '')
                            $ventas = $ventas->where('lotes.emp_terreno','=', $empresa2);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);

                        $ventas = $ventas->orWhere('contratos.status','=',1)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);


                        $ventas = $ventas->orWhere('contratos.status','=',0)
                        ->where('ins.elegido','=',1)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $ventas = $ventas->where('lotes.emp_constructora','=', $empresa);

                        if($publicidad != '')
                            $ventas = $ventas->where('contratos.publicidad_id','=', $publicidad);

                        $ventas = $ventas->orderBy('contratos.status','desc')
                        ->orderBy('contratos.fecha','asc')
                        ->get();

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('medios_publicitarios','contratos.publicidad_id','=','medios_publicitarios.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'lotes.emp_constructora','creditos.valor_terreno', 'lotes.emp_terreno',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
                                'contratos.avance_lote', 'modelos.nombre as modelo',
                                'medios_publicitarios.nombre as publicidad', 'contratos.publicidad_id',
                                'creditos.descripcion_promocion','creditos.descripcion_paquete','contratos.motivo_cancel',
                                'contratos.fecha_status')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',0)
                        ->whereBetween('contratos.fecha_status', [$request->fecha, $request->fecha2]);

                        if($empresa != '')
                            $cancelaciones = $cancelaciones->where('lotes.emp_constructora','=', $empresa);

                        if($empresa2 != '')
                            $cancelaciones = $cancelaciones->where('lotes.emp_terreno','=', $empresa2);

                        if($publicidad != '')
                            $cancelaciones = $cancelaciones->where('contratos.publicidad_id','=', $publicidad);

                        $cancelaciones = $cancelaciones->orderBy('contratos.fecha_status','asc')
                        ->get();

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($request->fecha);
                        $fecha1 = $fecha1->formatLocalized('%d de %B de %Y');

                        $fecha2 = new Carbon($request->fecha2);
                        $fecha2 = $fecha2->formatLocalized('%d de %B de %Y');

                        $periodo = 'Del '.$fecha1.' al '.$fecha2;

        
        return Excel::create('Reporte de ventas y cancelaciones', function($excel) use ($ventas,$cancelaciones,$periodo,$empresa){
            $excel->sheet('Ventas', function($sheet) use ($ventas,$periodo, $empresa){

                $sheet->mergeCells('A1:M1');
                $sheet->mergeCells('A2:M2');
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
                    
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'S' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                if($empresa == 'CONCRETANIA'){

                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
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
                    
    
                    $sheet->cells('A8:U8', function ($cells) {
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
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
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
                
                    $num='A8:T' . $cont;
                    $sheet->setBorder($num, 'thin');

                }
                else{
                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',
                        
                        'Descuento precio de casa o equipamiento',
                        'Descuento en el terreno',
                        'Costo de Alarma',
                        'Cuota de mantenimiento',
                        'Protecciones'
                    ]);
                    
    
                    $sheet->cells('A8:U8', function ($cells) {
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
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                            $lote->costo_descuento,
                            $lote->descuento_terreno,
                            $lote->costo_alarma,
                            $lote->costo_cuota_mant,
                            $lote->costo_protecciones,
                            $status,
                        ]);	
                    }
                
                    $num='A8:U' . $cont;
                    $sheet->setBorder($num, 'thin');
                }

                

                
            });

            $excel->sheet('Cancelaciones', function($sheet) use ($cancelaciones,$periodo, $empresa){

                $sheet->mergeCells('A1:N1');
                $sheet->mergeCells('A2:N2');
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
                    
                    'P' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                ));

                if($empresa == 'CONCRETANIA'){

                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Fecha de cancelación',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',
                        'Valor de terreno',
                        'Valor de construccion'
                    ]);
                    
    
                    $sheet->cells('A8:M8', function ($cells) {
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
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->fecha_status,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                            $lote->valor_terreno,
                            $lote->precio_venta - $lote->valor_terreno
                        ]);	
                    }
                
                    $num='A8:O' . $cont;
                    $sheet->setBorder($num, 'thin');

                }
                else{
                    $sheet->row(8, [
                        'No.',
                        'Fraccionamiento',
                        'Etapa',
                        'Manzana',
                        'Modelo',
                        'Lote',
                        'Cliente',
                        'Fecha de cancelación',
                        'Fecha de venta',
                        'Crédito',
                        'Institución',
                        'Medio Publicitario',
                        'Promoción / Paquete',
                        'Valor de escrituración',
                        
                    ]);
                    
    
                    $sheet->cells('A8:M8', function ($cells) {
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
                            $lote->modelo,
                            $lote->num_lote,
                            $lote->nombre.' '.$lote->apellidos,
                            $lote->fecha_status,
                            $lote->fecha,
                            $lote->tipo_credito,
                            $lote->institucion,
                            $lote->publicidad,
                            $paquete,
                            $lote->precio_venta,
                           
                        ]);	
                    }
                
                    $num='A8:P' . $cont;
                    $sheet->setBorder($num, 'thin');
                }

                

                
            });
            
        }
        
        )->download('xls');
    }

    public function reporteAcumulado(Request $request){
        $opcion = $request->opcion;

        $mes = $request->mes;
        $anio = $request->anio;

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $empresa = $request->empresa;

        switch($opcion){
            case 'Expedientes':{
                $expCreditos = $this->getRepExpedientes($mes, $anio, $empresa);
                $expContado = $this->getRepExpContado($mes, $anio, $empresa);
                $sinEntregar = $this->getSinEntregarRep($mes,$anio, $empresa);

                return ['expCreditos'=>$expCreditos,
                    'expContado'=>$expContado,
                    'pendientes'=>$sinEntregar
                   
                ];

                break;
            }
            case 'Escrituras':{
                $escrituras = $this->getEscriturasRep($mes, $anio, $empresa);
                $contadoSinEscrituras = $this->getContadoSinEscrituras($mes, $anio, $empresa);

                return [
                    'escrituras'=>$escrituras,
                    'contadoSinEscrituras'=>$contadoSinEscrituras
                ];

                break;
            }
            case 'Ingresos':{
                $ingresosCobranza = $this->getIngresosCobranza($fecha1,$fecha2, $empresa);

                return [
                    'ingresosCobranza'=>$ingresosCobranza
                ];

                break;
            }
        }
           

    }

    public function excelExpedientes(Request $request){

        $mes = $request->mes;
        $anio = $request->anio;

        $empresa = $request->empresa;

        $expCreditos = $this->getRepExpedientes($mes, $anio, $empresa);
        $expContado = $this->getRepExpContado($mes, $anio, $empresa);
        $sinEntregar = $this->getSinEntregarRep($mes,$anio, $empresa);



        return Excel::create('Reporte de expedientes', function($excel) use ($expCreditos,$expContado,$sinEntregar){
            $excel->sheet('Expedientes', function($sheet) use ($expCreditos,$sinEntregar){

                
                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE EXPEDIENTES ENTREGADOS');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);

                $cont = 7;
              
                foreach($expCreditos as $index => $lote) {
                    if($lote->flag == 1 && $lote->mes == 1){
                        $cont++;

                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos, 
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);	

                    }
                    
                }
            
                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });

            $excel->sheet('Expedientes de contado', function($sheet) use ($expContado){

                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE EXPEDIENTES DE CONTADO' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);

                $cont = 7;
                

               
                foreach($expContado as $index => $lote) {
                   
                        $cont++;

                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos, 
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);	

                    
                    
                }
            
                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });

            $excel->sheet('Pendientes de cobro', function($sheet) use ($sinEntregar){

                $sheet->mergeCells('A1:H4');
                $sheet->mergeCells('A5:H5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'PENDIENTES DE COBRO' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Emp. Const.'
                ]);

                $cont = 7;
                
               
                foreach($sinEntregar as $index => $lote) {
                   
                        $cont++;

                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos, 
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito,
                            $lote->emp_constructora
                        ]);	
              
                }
            
                $num='A5:H' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });
            
        }
        
        )->download('xls');


    }

    public function excelIngresos(Request $request){

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $empresa = $request->empresa;

        $ingresosCobranza = $this->getIngresosCobranza($fecha1,$fecha2, $empresa);

        return Excel::create('Reporte mensual de ingresos', function($excel) use ($ingresosCobranza,$empresa){
            $excel->sheet('Cobranza institucional', function($sheet) use ($ingresosCobranza,$empresa){

                
                $sheet->mergeCells('A1:K4');
                $sheet->mergeCells('A5:K5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'REPORTE DE INGRESOS (COBRANZA INSTITUCIONAL)');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

               


                    $sheet->cells('A7:K7', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Arial Narrow');
    
                        // Set font size
                        $cells->setFontSize(11);
    
                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
    

                    $sheet->setColumnFormat(array(
                        'G' => '$#,##0.00'
                    ));

                    $sheet->row(7,[
                        '', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 
                        'Monto Crédito Neto', 'Fecha', 'Banco','Empresa const', 'Firma de escrituras'
                    ]);
    
                    $cont = 7;
                  
                    foreach($ingresosCobranza as $index => $lote) {
                        
                            $cont++;
    
                            $sheet->row($cont, [
                                $index+1,
                                $lote->nombre.' '.$lote->apellidos, 
                                $lote->proyecto,
                                $lote->num_etapa,
                                $lote->manzana,
                                $lote->num_lote,
                                $lote->cant_depo,
                                $lote->fecha_deposito,
                                $lote->banco,
                                $lote->emp_constructora,
                                $lote->escrituras
                                
                            ]);	
    
                    }

                    $num='A5:K' . $cont;

                
                
                $sheet->setBorder($num, 'thin');
                
            });
            
        }
        
        )->download('xls');

    }

    public function excelEscrituras(Request $request){

        $mes = $request->mes;
        $anio = $request->anio;

        $empresa = $request->empresa;

        $escrituras = $this->getEscriturasRep($mes, $anio, $empresa);
        $contadoSinEscrituras = $this->getContadoSinEscrituras($mes, $anio, $empresa);

        return Excel::create('Reporte mensual de escrituras', function($excel) use ($escrituras,$contadoSinEscrituras,$empresa){
            $excel->sheet('Ventas de crédito', function($sheet) use ($escrituras,$empresa){

                
                $sheet->mergeCells('A1:J4');
                $sheet->mergeCells('A5:J5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE C.V.');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'ESCRITURAS VENTAS DE CRÉDITO');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A7:J7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                if($empresa == 'CONCRETANIA'){

                    $sheet->cells('A7:L7', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Arial Narrow');
    
                        // Set font size
                        $cells->setFontSize(11);
    
                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });
    

                    $sheet->setColumnFormat(array(
                        'H' => '$#,##0.00',
                        'I' => '$#,##0.00',
                        'K' => '$#,##0.00'
                    ));

                    $sheet->row(7,[
                        '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Valor de terreno', 'Valor de casa', 'Fecha de firma de escrituras', 'Valor de escrituración', 'Notaria'
                    ]);
    
                    $cont = 7;
                  
                    foreach($escrituras as $index => $lote) {
                        
                            $cont++;
    
                            $sheet->row($cont, [
                                $lote->id,
                                $lote->nombre.' '.$lote->apellidos, 
                                $lote->proyecto,
                                $lote->num_etapa,
                                $lote->manzana,
                                $lote->num_lote,
                                $lote->tipo_credito.' ('.$lote->institucion.')',
                                $lote->valor_terreno,
                                $lote->valor_escrituras-$lote->valor_terreno,
                                $lote->fecha_firma_esc,
                                $lote->valor_escrituras,
                                $lote->notaria
                                
                            ]);	
    
                        
                        
                    }

                    $num='A5:L' . $cont;

                }
                else{

                    $sheet->setColumnFormat(array(
                        'I' => '$#,##0.00'
                    ));

                    $sheet->row(7,[
                        '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 'Manzana', 'Lote', 'Crédito', 'Fecha de firma de escrituras', 'Valor de escrituración', 'Notaria'
                    ]);
    
                    $cont = 7;
                  
                    foreach($escrituras as $index => $lote) {
                        
                            $cont++;
    
                            $sheet->row($cont, [
                                $lote->id,
                                $lote->nombre.' '.$lote->apellidos, 
                                $lote->proyecto,
                                $lote->num_etapa,
                                $lote->manzana,
                                $lote->num_lote,
                                $lote->tipo_credito.' ('.$lote->institucion.')',
                                $lote->fecha_firma_esc,
                                $lote->valor_escrituras,
                                $lote->notaria
                                
                            ]);	
    
                        
                        
                    }

                    $num='A5:J' . $cont;

                }

                
            
                
                $sheet->setBorder($num, 'thin');
                
            });

            $excel->sheet('Pendientes de escriturar', 
                function($sheet) use ($contadoSinEscrituras){

                $sheet->mergeCells('A1:I4');
                $sheet->mergeCells('A5:I5');
                $sheet->mergeCells('A6:B6');
                $sheet->mergeCells('C6:F6');

                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue( '');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'CONTADOS PENDIENTES DE ESCRITURAR' );
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(12);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cell('C6', function($cell) {

                    $cell->setValue(  'Ubicación / Plantio');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });

                $sheet->cells('A7:I7', function ($cells) {
                    $cells->setBackground('#052154');
                    $cells->setFontColor('#ffffff');
                    // Set font family
                    $cells->setFontFamily('Arial Narrow');

                    // Set font size
                    $cells->setFontSize(11);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row(7,[
                    '# Ref.', 'Cliente','Fraccionamiento', 'Etapa', 
                    'Manzana', 'Lote', 'Crédito', 'Fecha de venta', 'Responsable'
                ]);

                $cont = 7;
                

               
                foreach($contadoSinEscrituras as $index => $lote) {
                   
                        $cont++;

                        $sheet->row($cont, [
                            $lote->id,
                            $lote->nombre.' '.$lote->apellidos, 
                            $lote->proyecto,
                            $lote->num_etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->tipo_credito.' ('.$lote->institucion.')',
                            $lote->fecha,
                            $lote->nombre_gestor
                        ]);	

                    
                    
                }
            
                $num='A5:I' . $cont;
                $sheet->setBorder($num, 'thin');
                
            });
            
        }
        
        )->download('xls');



    }

    private function getRepExpedientes($mes, $anio, $empresa){

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
                            'lotes.manzana','lotes.num_lote', 'lotes.emp_constructora',
                            DB::raw("SUM(dep_creditos.cant_depo) as totalDep")
                            )
                ->where('dep_creditos.fecha_deposito','<',$fecha);

                if($empresa != '')
                    $depositos = $depositos->where('lotes.emp_constructora','=',$empresa);

                $depositos = $depositos->groupBy('ins.credito_id')
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
                        ->where('dep_creditos.banco','!=','0000000-Grupo Cumbres')
                        ->whereYear('dep_creditos.fecha_deposito',$anio)
                        ->whereMonth('dep_creditos.fecha_deposito',$mes);
                        
                    if($empresa != '')
                        $act = $act->where('lotes.emp_constructora','=',$empresa);

                    $act = $act->get();

                $contrato = Contrato::select('send_exp','received_exp','fecha_audit')->where('id','=',$deposito->id)->get();

                $deposito->send_exp = $contrato[0]->send_exp;
                $deposito->received_exp = $contrato[0]->received_exp;
                $deposito->fecha_audit = $contrato[0]->fecha_audit;
                
                $deposito->totalCredito = round($inst[0]->monto_credito + $inst[0]->segundo_credito,2);
                $deposito->totalDep = round($deposito->totalDep,2);
                $deposito->tipo_credito = $inst[0]->tipo_credito;
                $deposito->institucion = $inst[0]->institucion;
                $deposito->flag = 0;
                $deposito->mes = 0;
                if($deposito->totalCredito <= $deposito->totalDep)
                    $deposito->flag = 1;
                if(sizeOf($act)){
                    $deposito->mes = 1;
                }
            
            }
        }

        return $depositos;
    }

    private function getSinEntregarRep($mes, $anio, $empresa){
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
                            'lotes.manzana','lotes.num_lote','lotes.emp_constructora',
                            'inst_seleccionadas.cobrado','inst_seleccionadas.monto_credito'
                            )
                ->where('inst_seleccionadas.tipo','=',0)
                ->where('inst_seleccionadas.elegido','=',1)
                ->where('inst_seleccionadas.status','=',2)
                ->where('expedientes.fecha_firma_esc','!=', NULL)
                ->where('contratos.status','=',3)
                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                ->whereRaw('inst_seleccionadas.cobrado < inst_seleccionadas.monto_credito');

                if($empresa != '')
                    $sinEntregar = $sinEntregar->where('lotes.emp_constructora','=',$empresa);

            $sinEntregar = $sinEntregar->orWhere('inst_seleccionadas.tipo','=',1)
                ->where('inst_seleccionadas.elegido','=',0)
                ->whereRaw('inst_seleccionadas.cobrado != inst_seleccionadas.monto_credito')
                ->where('inst_seleccionadas.status','=',2)
                ->where('expedientes.fecha_firma_esc','!=', NULL)
                ->where('contratos.status','=',3)
                ->where('inst_seleccionadas.tipo_credito','<','Crédito Directo');

                if($empresa != '')
                    $sinEntregar = $sinEntregar->where('lotes.emp_constructora','=',$empresa);

                $sinEntregar = $sinEntregar->orderBy('contratos.fecha','asc')->get();

        return $sinEntregar;
    }
    
    private function getRepExpContado($mes, $anio, $empresa){
        $expContado = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.num_lote', 'lotes.emp_constructora',
                        'contratos.received_exp','contratos.send_exp', 'contratos.fecha_audit',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','expedientes.fecha_firma_esc')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','=','Crédito Directo')
                ->whereMonth('expedientes.fecha_firma_esc',$mes)
                ->whereYear('expedientes.fecha_firma_esc',$anio);

                if($empresa != '')
                    $expContado = $expContado->where('lotes.emp_constructora','=',$empresa);


                $expContado = $expContado->orderBy('expedientes.fecha_firma_esc','asc')->get();

        return $expContado;
    }

    private function getEscriturasRep($mes, $anio, $empresa){
        $escrituras = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'creditos.valor_terreno',
                        'lotes.manzana','lotes.num_lote','ins.tipo_credito','ins.institucion',
                        'expedientes.doc_escrituras','expedientes.doc_date',
                        'expedientes.fecha_firma_esc','expedientes.valor_escrituras','expedientes.notaria')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('expedientes.fecha_firma_esc','!=',NULL)
                ->whereMonth('expedientes.fecha_firma_esc',$mes)
                ->whereYear('expedientes.fecha_firma_esc',$anio);

                if($empresa != '')
                    $escrituras = $escrituras->where('lotes.emp_constructora','=',$empresa);

            $escrituras = $escrituras->orderBy('expedientes.fecha_firma_esc','desc')->get();
        
        return $escrituras;
    }

    private function getContadoSinEscrituras($mes, $anio, $empresa){
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
            ->where('ins.tipo_credito','=','Crédito Directo');

            if($empresa != '')
                    $contadoSinEscrituras = $contadoSinEscrituras->where('lotes.emp_constructora','=',$empresa);

            $contadoSinEscrituras = $contadoSinEscrituras->orderBy('contratos.fecha')->get();
    
        return $contadoSinEscrituras;
    }

    private function getIngresosCobranza($fecha1, $fecha2, $empresa){
        $ingresosCobranza = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('dep_creditos as dep','ins.id','=','dep.inst_sel_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.emp_constructora','contratos.id',
                        'lotes.manzana','lotes.num_lote','dep.cant_depo','dep.fecha_deposito','dep.banco')

                ->whereBetween('dep.fecha_deposito',[$fecha1, $fecha2]);

                if($empresa != '')
                    $ingresosCobranza = $ingresosCobranza->where('lotes.emp_constructora','=',$empresa);

            $ingresosCobranza = $ingresosCobranza->orderBy('dep.fecha_deposito')->get();

            foreach($ingresosCobranza as $index => $ingreso){
                $exp = Expediente::select('fecha_firma_esc')->where('id','=',$ingreso->id)->get();

                if(sizeOf($exp)){
                    $ingreso->escrituras = $exp[0]->fecha_firma_esc;
                }
                else{
                    $ingreso->escrituras = '';
                }
            }

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
    
                $lotes = $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

                        $lotes = $lotes->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        
                        $lotes = $lotes->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
            
                  
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
    
                        $lotes = $lotes->where('lotes.credito_puente','like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

                        $lotes = $lotes->orWhere('lotes.credito_puente','like','EN PROCESO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        
                        $lotes = $lotes->orWhere('lotes.credito_puente','like','LIQUIDADO%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1);

                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        
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

                $lotes = $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        
                        
        $lotes2 = $lotes;
        $lotes2 = $lotes2->get();

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

                $lotes = $lotes->where('lotes.credito_puente','not like','NO TIENE CREDITO PUENTE%')
                        ->whereNotIn('lotes.id',$indiv)
                        ->where('licencias.avance','>',1)
                        ->where('lotes.credito_puente','NOT LIKE','EN PROCESO%')
                        ->where('lotes.credito_puente','NOT LIKE','LIQUIDADO%');
                        if($request->proyecto != '')
                            $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);
                        if($request->etapa != '')
                            $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);
                        
                        
        $lotes2 = $lotes;
        $lotes2 = $lotes2->get();

        $lotes = $lotes->orderBy('proyecto','asc')
                        ->orderBy('etapas.num_etapa','asc')
                        ->orderBy('etapas.num_etapa','asc')->get();
       
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

        $fechaIni = $request->fecha1;
        $fechaFin = $request->fecha2;
        
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

            $lotesTerm = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','>=',90)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);
            $lotesProc = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->where('licencias.avance','<',90)
                ->where('lotes.habilitado','=',1)->where('modelos.nombre','=',$modelo->nombre);

            if($fraccionamiento != ''){
                $lotesTerm = $lotesTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                $lotesProc = $lotesProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
            }
                
            if($etapa != ''){
                $lotesTerm = $lotesTerm->where('lotes.etapa_id','=',$etapa);
                $lotesProc = $lotesProc->where('lotes.etapa_id','=',$etapa);
            }
                
            $lotesTerm = $lotesTerm->count();
            $lotesProc = $lotesProc->count();

            $modelo->total = $lotesProc + $lotesTerm;
            $modelo->lotesTerm = $lotesTerm;
            $modelo->lotesProc = $lotesProc;

            $indivContadoProc =  Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.liquidado','=',1)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);

            $indivContadoTerm =  Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.liquidado','=',1)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);

                $indivContadoTerm = $indivContadoTerm->where('licencias.avance','>=',90);
                $indivContadoProc = $indivContadoProc->where('licencias.avance','<',90);

                                if($fraccionamiento != ''){
                                    $indivContadoTerm=$indivContadoTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                    $indivContadoProc=$indivContadoProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                }
                                    
                                if($etapa != ''){
                                    $indivContadoTerm=$indivContadoTerm->where('lotes.etapa_id','=',$etapa);
                                    $indivContadoProc=$indivContadoProc->where('lotes.etapa_id','=',$etapa);
                                }
                                
                                $indivContadoTerm = $indivContadoTerm->distinct('contratos.id')
                                                            ->count('contratos.id');
                                $indivContadoProc = $indivContadoProc->distinct('contratos.id')
                                                            ->count('contratos.id');

                $indivContado = $indivContadoTerm + $indivContadoProc;
            
            $indivCreditoTerm = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);

            $indivCreditoProc = Contrato::join('expedientes','contratos.id','=','expedientes.id')
                                ->join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->where('contratos.status','=',3)
                                ->where('expedientes.fecha_firma_esc','!=',NULL)
                                ->where('inst_seleccionadas.elegido', '=', '1')
                                ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                                ->where('modelos.nombre','=',$modelo->nombre);

                $indivCreditoTerm = $indivCreditoTerm->where('licencias.avance','>=',90);
                $indivCreditoProc = $indivCreditoProc->where('licencias.avance','<',90);

                                if($fraccionamiento != ''){
                                    $indivCreditoTerm=$indivCreditoTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                    $indivCreditoProc=$indivCreditoProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                } 

                                if($etapa != ''){
                                    $indivCreditoTerm=$indivCreditoTerm->where('lotes.etapa_id','=',$etapa);
                                    $indivCreditoProc=$indivCreditoProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                                }

                                $indivCreditoTerm=$indivCreditoTerm->distinct('contratos.id')
                                                            ->count('contratos.id');
                                $indivCreditoProc=$indivCreditoProc->distinct('contratos.id')
                                                            ->count('contratos.id');

                $indivCredito = $indivCreditoTerm + $indivCreditoProc;

            $contratosProc = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('licencias','lotes.id','=','licencias.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->where('contratos.status','=',3)
                            ->where('licencias.avance','<',90)
                            ->where('modelos.nombre','=',$modelo->nombre);

                            if($fraccionamiento != '')
                                $contratosProc=$contratosProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);

                            if($etapa != '')
                                $contratosProc=$contratosProc->where('lotes.etapa_id','=',$etapa);

                            $contratosProc=$contratosProc->orWhere('contratos.status','=',1)
                                ->where('licencias.avance','<',90)
                                ->where('modelos.nombre','=',$modelo->nombre);

                            if($fraccionamiento != '')
                                $contratosProc=$contratosProc->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                            if($etapa != '')
                                $contratosProc=$contratosProc->where('lotes.etapa_id','=',$etapa);
                           
                            $contratosProc=$contratosProc->distinct('contratos.id')
                                ->count('contratos.id');
            
            $contratosTerm = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('licencias','lotes.id','=','licencias.id')
                            ->join('modelos','lotes.modelo_id','=','modelos.id')
                            ->where('contratos.status','=',3)
                            ->where('licencias.avance','>=',90)
                            ->where('modelos.nombre','=',$modelo->nombre);

                            if($fraccionamiento != '')
                                $contratosTerm=$contratosTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);

                            if($etapa != '')
                                $contratosTerm=$contratosTerm->where('lotes.etapa_id','=',$etapa);

                            $contratosTerm=$contratosTerm->orWhere('contratos.status','=',1)
                                ->where('licencias.avance','>=',90)
                                ->where('modelos.nombre','=',$modelo->nombre);

                            if($fraccionamiento != '')
                                $contratosTerm=$contratosTerm->where('lotes.fraccionamiento_id','=',$fraccionamiento);
                            if($etapa != '')
                                $contratosTerm=$contratosTerm->where('lotes.etapa_id','=',$etapa);
                           
                            $contratosTerm=$contratosTerm->distinct('contratos.id')
                            ->count('contratos.id');

            $contratos = $contratosTerm + $contratosProc;

            $indiv = $indivContado + $indivCredito;

            $indivTerm = $indivCreditoTerm + $indivContadoTerm;
            $indivProc = $indivCreditoProc + $indivContadoProc;

            $modelo->indiv = $indiv;

            $modelo->vendida = $contratos - $indiv;

            $modelo->vendidaTerm = $contratosTerm - $indivTerm;

            $modelo->vendidaProc = $contratosProc - $indivProc;

            $modelo->total_vendidas = $modelo->indiv + $modelo->vendida;

            $modelo->disponible = $modelo->total - ($modelo->indiv + $modelo->vendida );

            $modelo->disponibleProc = $lotesProc - ($indivProc + $modelo->vendidaProc );

            $modelo->disponibleTerm = $lotesTerm - ($indivTerm + $modelo->vendidaTerm );

            
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

                if($request->desde != '' && $request->hasta != ''){
                    $resumen = $resumen->whereBetween('solic_detalles.created_at', [$request->desde.' 00:00:00', $request->hasta.' 23:59:59']);
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

                if($request->desde != '' && $request->hasta != ''){
                    $resumen = $resumen->whereBetween('solic_detalles.created_at', [$request->desde, $request->hasta]);
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

    public function formSubmitEscrituras(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $escrituras = Expediente::select('doc_escrituras', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($escrituras->isEmpty() == 1) {
            $fileName = time() . '.' . $request->archivo->getClientOriginalExtension();
            $moved =  $request->archivo->move(public_path('/files/escrituras'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $escrituras = Expediente::findOrFail($request->id);
                $escrituras->doc_escrituras = $fileName;
                $escrituras->doc_date = $fecha;
                $escrituras->save(); //Insert

            }

            return back();
        } else {
            $pathAnterior = public_path() . '/files/escrituras/' . $escrituras[0]->pdf;
            File::delete($pathAnterior);

            $fileName = time() . '.' . $request->archivo->getClientOriginalExtension();
            $moved =  $request->archivo->move(public_path('/files/escrituras'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $escrituras = Expediente::findOrFail($request->id);
                $escrituras->doc_escrituras = $fileName;
                $escrituras->doc_date = $fecha;
                $escrituras->save(); //Insert

            }

            return back();
        }
    }

    public function downloadFile($fileName)
    {

        $pathtoFile = public_path() . '/files/escrituras/' . $fileName;
        return response()->download($pathtoFile);
    }
    
}
