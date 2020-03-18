<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;
use Excel;
use Carbon\Carbon;

use App\Cliente;
use App\Vendedor;

class ReportesController extends Controller
{
    public function reporteInventario(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $proyecto = $request->proyecto;
        $etapa = $request->etapa;

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

        $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                    ->join('users','personal.id','=','users.id')
                    ->select('vendedores.id','personal.nombre','personal.apellidos')
                    ->where('users.condicion','=',1)
                    ->get();

        
        foreach($vendedores as $index => $vendedor){

            if($fecha1==''){
                $vendedor->clientes = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','!=',7)->count();
                $vendedor->tipoA = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',2)->count();
                $vendedor->tipoB = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',3)->count();
                $vendedor->tipoC = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',4)->count();
                $vendedor->noViable = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',1)->count();
            }
            else{
                $vendedor->clientes = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','!=',7)->
                    whereBetween('created_at', [$fecha1, $fecha2])->count();
                $vendedor->tipoA = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',2)->
                    whereBetween('created_at', [$fecha1, $fecha2])->count();
                $vendedor->tipoB = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',3)->
                    whereBetween('created_at', [$fecha1, $fecha2])->count();
                $vendedor->tipoC = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',4)->
                    whereBetween('created_at', [$fecha1, $fecha2])->count();
                $vendedor->noViable = Cliente::where('vendedor_id','=',$vendedor->id)->where('clasificacion','=',1)->
                    whereBetween('created_at', [$fecha1, $fecha2])->count();

            }
            

        }

        

        return ['vendedores' => $vendedores];
    }
    
}
