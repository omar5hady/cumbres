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

                if($vendedor->clientes != 0) {
                    $vendedor->por_venta=(($vendedor->ventas-$vendedor->canceladas)/$vendedor->clientes)*100;
                    $vendedor->por_cancel=(($vendedor->canceladas)/$vendedor->clientes)*100;
                }
                else{
                    $vendedor->por_venta= 0;
                    $vendedor->por_cancel= 0;
                }

                if($vendedor->nv!=0){
                    $vendedor->por_bat=($vendedor->clientes/$vendedor->nv)*100;
                }
                else{
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

    public function reporteVentas(Request $request){

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
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
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
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
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta')
                        ->where('ins.elegido','=',1)
                        ->where('contratos.status','=',3)
                        ->whereBetween('contratos.fecha', [$request->fecha, $request->fecha2])
                        ->orderBy('contratos.fecha','asc')
                        ->get();

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('personal as p','creditos.prospecto_id','=','p.id')
                        ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                        ->join('etapas as et','lotes.etapa_id','=','et.id')
                        ->select('lotes.manzana','lotes.num_lote','f.nombre as proyecto','et.num_etapa','p.nombre', 'p.apellidos',
                                'contratos.fecha','ins.tipo_credito','ins.institucion','creditos.precio_venta',
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

                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
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
                    'J' => '$#,##0.00',
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
                    'Valor de escrituración'
                ]);
                

                $sheet->cells('A8:J8', function ($cells) {
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
                        $lote->precio_venta,
                    ]);	
                }
            
                $num='A8:J' . $cont;
                $sheet->setBorder($num, 'thin');

                
            });

            $excel->sheet('Cancelaciones', function($sheet) use ($cancelaciones,$periodo){

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
                    'K' => '$#,##0.00',
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
                    'Valor de escrituración'
                ]);
                

                $sheet->cells('A8:K8', function ($cells) {
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
                        $lote->precio_venta,
                    ]);	
                }
            
                $num='A8:K' . $cont;
                $sheet->setBorder($num, 'thin');

                
            });
            
        }
        
        )->download('xls');
    }

    public function reporteAcumulado(Request $request){
        $mes = $request->mes;
        $anio = $request->anio;

        $expCreditos = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','expedientes.created_at')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','!=','Crédito Directo')
                ->whereMonth('expedientes.created_at',$mes)
                ->whereYear('expedientes.created_at',$anio)
                ->orderBy('expedientes.created_at','asc')->get();

        $expContado = Expediente::join('contratos','expedientes.id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','expedientes.created_at')
                ->where('contratos.status','=',3)
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','=','Crédito Directo')
                ->whereMonth('expedientes.created_at',$mes)
                ->whereYear('expedientes.created_at',$anio)
                ->orderBy('expedientes.created_at','asc')->get();

        $sinEntregar = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('inst_seleccionadas as ins', 'creditos.id', '=', 'ins.credito_id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('personal as p','creditos.prospecto_id','=','p.id')
                ->join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as et','lotes.etapa_id','=','et.id')
                ->select('contratos.id','p.nombre','p.apellidos','f.nombre as proyecto','et.num_etapa',
                        'lotes.manzana','ins.tipo_credito','ins.institucion','contratos.fecha')
                ->where('contratos.status','=',3)
                ->where('contratos.integracion','=',0)
                ->where('ins.elegido','=',1)
                ->where('ins.tipo_credito','=','Crédito Directo')
                ->orderBy('contratos.fecha','asc')->get();
        
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
        

        
        
        return ['expCreditos'=>$expCreditos,
                'expContado'=>$expContado,
                'pendientes'=>$sinEntregar,
                'escrituras'=>$escrituras,
                'contadoSinEscrituras'=>$contadoSinEscrituras,
                'ingresosCobranza'=>$ingresosCobranza

            ];

    }
    
}
