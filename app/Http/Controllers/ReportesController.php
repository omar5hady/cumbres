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
use App\Historial_descartado;

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

        
        foreach($vendedores as $index => $vendedor){

            if($fecha1==''){
                $vendedor->clientes = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','!=',7)->count();
                $vendedor->tipoA = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',2)->count();
                $vendedor->tipoB = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',3)->count();
                $vendedor->tipoC = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',4)->count();
                $vendedor->noViable = Cliente::where('user_alta','=',$vendedor->id)->where('clasificacion','=',1)->count();

                $vendedor->ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->where('creditos.vendedor_id','=',$vendedor->id)
                    ->where('contratos.status','=',3)
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

                if($vendedor->clientes != 0){
                    $vendedor->por_venta=(($vendedor->ventas-$vendedor->canceladas)/$vendedor->clientes)*100;
                    $vendedor->por_cancel=(($vendedor->canceladas)/$vendedor->clientes)*100;
                }
                else{
                    $vendedor->por_venta=0;
                    $vendedor->por_cancel=0;
                }

                if($vendedor->nv != 0){
                    $vendedor->por_bat=($vendedor->nv/$vendedor->clientes)*100;
                }
                else{;
                    $vendedor->por_bat=0;
                }
                

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

                $vendedor->por_venta=(($vendedor->ventas-$vendedor->canceladas)/$vendedor->clientes)*100;
                $vendedor->por_cancel=(($vendedor->canceladas)/$vendedor->clientes)*100;
                $vendedor->por_bat=($vendedor->clientes/$vendedor->nv)*100;

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

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9 = 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId)->count();

            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',95)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [25, 95])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','<',25)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->cobradas = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('contratos.status','=',3)
                                ->where('contratos.saldo','<=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

            $lote->procVendidaNoCobrada = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->where('contratos.status','=',3)
                                ->whereBetween('licencias.avance', [0, 95])
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->orWhere('contratos.status','=',1)
                                ->whereBetween('licencias.avance', [0, 95])
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

            $lote->termVendidaNoCobrada = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->where('contratos.status','=',3)
                                ->where('licencias.avance','>',95)
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->orWhere('contratos.status','=',1)
                                ->where('licencias.avance','>',95)
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

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
                'total9'=>$total9
            ];
    }

    public function excelReporteLotesVentas(Request $request){

        $lotes = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                        ->select('fraccionamientos.id as proyectoId','etapas.id as etapaId',
                                    'etapas.num_etapa','fraccionamientos.nombre as proyecto')
                        ->orderBy('proyecto','asc')->orderBy('num_etapa','asc')->get();

        $total1= $total2= $total3= $total4= $total5= $total6= $total7= $total8= $total9 = 0;

        foreach($lotes as $index => $lote){
            $lote->lotes = Lote::where('fraccionamiento_id','=',$lote->proyectoId)
                                ->where('etapa_id','=',$lote->etapaId)->count();

            $lote->terminadaDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','>',95)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->procesoDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->whereBetween('licencias.avance', [25, 95])
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->sinAvanceDisponible = Lote::join('licencias','lotes.id','=','licencias.id')
                                ->where('licencias.avance','<',25)
                                ->where('lotes.contrato','=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)->count();

            $lote->cobradas = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->where('contratos.status','=',3)
                                ->where('contratos.saldo','<=',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

            $lote->procVendidaNoCobrada = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->where('contratos.status','=',3)
                                ->whereBetween('licencias.avance', [0, 95])
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->orWhere('contratos.status','=',1)
                                ->whereBetween('licencias.avance', [0, 95])
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

            $lote->termVendidaNoCobrada = Contrato::join('creditos','contratos.id', '=', 'creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->where('contratos.status','=',3)
                                ->where('licencias.avance','>',95)
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->orWhere('contratos.status','=',1)
                                ->where('licencias.avance','>',95)
                                ->where('contratos.saldo','>',0)
                                ->where('lotes.fraccionamiento_id','=',$lote->proyectoId)
                                ->where('lotes.etapa_id','=',$lote->etapaId)
                                ->count();

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

        }

        return Excel::create('Reporte', function($excel) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9){
            $excel->sheet('Reoorte lotes', function($sheet) use ($lotes, $total1, $total2, $total3, $total4, $total5, $total6, $total7, $total8, $total9){

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
                    'Disponibles Sin Avance'
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

                

                foreach($lotes as $index => $lote) {
                    $cont++;

                    $sheet->row($index+2, [
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

                    ]);	
                }
                $sheet->row($cont,[
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
                    
                ]);
            
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');

                $sheet->cells('A'.$cont.':'.'K'.$cont, function ($cells) {
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
    
}
