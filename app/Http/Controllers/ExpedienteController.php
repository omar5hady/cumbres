<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;
use App\Obs_expediente;
use Auth;
use App\Expediente;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use App\Liquidacion;
use App\inst_seleccionada;
use App\Gasto_admin;
use NumerosEnLetras;
use App\Lote;
use App\Credito;
use App\Entrega;
use App\Avaluo;
use App\Bono_recomendado;
use App\Http\Controllers\BonoRecomendadoController;

class ExpedienteController extends Controller
{
    public function indexContratos(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            //->leftjoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->select(
                'contratos.id as folio',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.foto_acta',
                'licencias.visita_avaluo',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'lotes.regimen_condom',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                'clientes.coacreditado',
                'lotes.credito_puente',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                //'avaluos.pdf',
                'contratos.detenido',
                DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                            WHERE pagos_contratos.tipo_pagare = 0
                            and pagos_contratos.contrato_id = contratos.id
                            and pagos_contratos.pagado != 3) as totPagare"),
                DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                            WHERE pagos_contratos.tipo_pagare = 0
                            and pagos_contratos.contrato_id = contratos.id
                            and pagos_contratos.pagado != 3) as totRest")
            );


        if ($buscar == '') {
            $contratos = $query
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 0)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->orderBy('licencias.visita_avaluo','asc')
                ->paginate(8);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 0)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->paginate(8);
            } else {

                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = $query
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 0)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar)
                        ->paginate(8);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 0)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->paginate(8);
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 0)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                ->paginate(8);
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 0)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                    ->paginate(8);
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = $query
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 0)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                        ->paginate(8);
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = $query
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 0)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                            ->paginate(8);
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 0)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                                ->paginate(8);
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = $query
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->paginate(8);
                                            } else {
                                                if ($criterio == 'v.nombre' && $buscar != '') {
                                                    $contratos = $query
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->paginate(8);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();

                if(sizeof($avaluos)){
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->pdf = '';
                }
                            
                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }

        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos,
        ];
    }

    public function listarObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_expediente::select('observacion','usuario','created_at')
        ->where('contrato_id','=', $request->folio)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_expediente();
        $observacion->contrato_id = $request->folio;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }

    public function exportExcel(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    'licencias.avance as avance_lote',
                    'licencias.visita_avaluo',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id'
                );

        if ($buscar == '') {
            $contratos = $query
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 0)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2)
                ->get();
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 0)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->get();
            } else {
                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = $query
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 0)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar)
                        ->get();
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 0)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->get();
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 0)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                ->get();
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 0)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                    ->get();
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = $query
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 0)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                        ->get();
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = $query
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 0)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                            ->get();
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 0)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%')
                                                ->get();
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = $query
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 0)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->get();
                                            } else {
                                                if ($criterio == 'v.nombre' && $buscar != '') {
                                                    $contratos = $query
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('v.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->get();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        
        return Excel::create('expediente', function($excel) use ($contratos){
            $excel->sheet('contratos', function($sheet) use ($contratos){              
                $sheet->row(1, [
                    '# Folio', 'Cliente' ,'Asesor', 'Proyecto', 'Etapa', 'Manzana', 'Lote',
                    '% Avance','Visita de avaluo' ,'Firma', 'Tipo de credito','Institucion Financiera','Solicitud de avaluo','Aviso preventivo',
                    'Regimen en condominio','Conyuge'
                ]);
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
                foreach($contratos as $index => $contrato) {

                    $cont++; 

                    if($contrato->regimen_condom == 1){
                        $regimen = 'Si';
                    }else{
                        $regimen = 'No';
                    }

                    $avaluo_prev = '';
                    if($contrato->avaluo_preventivo == "0000-01-01"){
                        $avaluo_prev = 'No aplica';
                    }
                    else{
                        $avaluo_prev = $contrato->avaluo_preventivo;
                    }

                    $aviso_prev = '';
                    if($contrato->aviso_prev == "0000-01-01"){
                        $aviso_prev = 'No aplica';
                    }
                    elseif($contrato->aviso_prev){
                        if($contrato->aviso_prev_venc)
                            $aviso_prev = 'Vencimiento: '.$contrato->aviso_prev;
                        else{
                            $aviso_prev = 'Solicitud: '.$contrato->aviso_prev;
                        }
                    }
                    
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->avance_lote.'%',
                        $contrato->visita_avaluo,
                        $contrato->fecha_status,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $avaluo_prev,
                        $aviso_prev,
                        $regimen,
                        $contrato->nombre_conyuge
                   
                    ]);	
                }
                $num='A1:O' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }

    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $expediente = new Expediente();
        $expediente->id = $request->folio;
        $expediente->fecha_integracion = $hoy;
        $expediente->gestor_id = $request->gestor_id;
        $expediente->save();

        $contrato = Contrato::findOrFail($request->folio);
        $contrato->integracion = 1;
        $contrato->save();
    }

    public function indexAsignarGestor(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('personal as g','expedientes.gestor_id','=','g.id')
            ->select(
                'contratos.id as folio',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion', 
                'expedientes.gestor_id',                
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor")
            );


        if ($buscar == '') {
            $contratos = $query
                ->where('i.elegido', '=', 1)
                ->where('contratos.integracion', '=', 1)
                ->where('contratos.status', '!=', 0)
                ->where('contratos.status', '!=', 2);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre') {
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.integracion', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where($criterio, 'like', '%' . $buscar . '%');
            } else {

                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == '') {
                    $contratos = $query
                        ->where('i.elegido', '=', 1)
                        ->where('contratos.integracion', '=', 1)
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where($criterio, '=', $buscar);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == '') {
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.integracion', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa);
                    } else {
                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == '') {
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.integracion', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where($criterio, '=', $buscar)
                                ->where('creditos.manzana', 'like', '%' . $b_manzana . '%');
                        } else {
                            if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != '') {
                                $contratos = $query
                                    ->where('i.elegido', '=', 1)
                                    ->where('contratos.integracion', '=', 1)
                                    ->where('contratos.status', '!=', 0)
                                    ->where('contratos.status', '!=', 2)
                                    ->where($criterio, '=', $buscar)
                                    ->where('creditos.num_lote', 'like', '%' . $b_lote . '%');
                            } else {
                                if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == '') {
                                    $contratos = $query
                                        ->where('i.elegido', '=', 1)
                                        ->where('contratos.integracion', '=', 1)
                                        ->where('contratos.status', '!=', 0)
                                        ->where('contratos.status', '!=', 2)
                                        ->where($criterio, '=', $buscar)
                                        ->where('lotes.etapa_id', '=', $b_etapa)
                                        ->where('creditos.manzana', 'like', '%' . $b_manzana . '%');
                                } else {
                                    if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != '') {
                                        $contratos = $query
                                            ->where('i.elegido', '=', 1)
                                            ->where('contratos.integracion', '=', 1)
                                            ->where('contratos.status', '!=', 0)
                                            ->where('contratos.status', '!=', 2)
                                            ->where($criterio, '=', $buscar)
                                            ->where('lotes.etapa_id', '=', $b_etapa)
                                            ->where('creditos.manzana', 'like', '%' . $b_manzana . '%')
                                            ->where('creditos.num_lote', 'like', '%' . $b_lote . '%');
                                    } else {
                                        if ($criterio == 'lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != '') {
                                            $contratos = $query
                                                ->where('i.elegido', '=', 1)
                                                ->where('contratos.integracion', '=', 1)
                                                ->where('contratos.status', '!=', 0)
                                                ->where('contratos.status', '!=', 2)
                                                ->where($criterio, '=', $buscar)
                                                ->where('lotes.etapa_id', '=', $b_etapa)
                                                ->where('creditos.num_lote', 'like', '%' . $b_lote . '%');
                                        } else {
                                            if ($criterio == 'c.nombre' && $buscar != '') {
                                                $contratos = $query
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 1)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2)
                                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                                    ->orWhere('c.apellidos', 'like', '%' . $buscar . '%')
                                                    ->where('i.elegido', '=', 1)
                                                    ->where('contratos.integracion', '=', 1)
                                                    ->where('contratos.status', '!=', 0)
                                                    ->where('contratos.status', '!=', 2);
                                            } else {
                                                if ($criterio == 'g.nombre' && $buscar != '') {
                                                    $contratos = $query
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 1)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2)
                                                        ->where($criterio, 'like', '%' . $buscar . '%')
                                                        ->orWhere('g.apellidos', 'like', '%' . $buscar . '%')
                                                        ->where('i.elegido', '=', 1)
                                                        ->where('contratos.integracion', '=', 0)
                                                        ->where('contratos.status', '!=', 0)
                                                        ->where('contratos.status', '!=', 2);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $contratos = $contratos->paginate(10);

        return [
            'pagination' => [
                'total'        => $contratos->total(),
                'current_page' => $contratos->currentPage(),
                'per_page'     => $contratos->perPage(),
                'last_page'    => $contratos->lastPage(),
                'from'         => $contratos->firstItem(),
                'to'           => $contratos->lastItem(),
            ],
            'contratos' => $contratos,
        ];
    }

    public function asignarGestor(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->gestor_id =  $request->gestor_id;
        $asignar->save();
    }

    public function indexIngresarExp(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0; 
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'contratos.detenido',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'lotes.calle','lotes.numero','lotes.interior'
            );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','=',NULL);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','=',NULL)
                    ->where('expedientes.gestor_id','=',Auth::user()->id);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar)
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                    }
                }
    
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();
                
                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }

        $contador = $contratos->count();

        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function ingresarExp(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $asignar = Expediente::findOrFail($request->folio);
        $asignar->fecha_ingreso =  $request->fecha_ingreso;
        $asignar->valor_escrituras =  $request->valor_escrituras;
        $asignar->save();
    }

    public function inscribirInfonavit(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit =  $request->fecha_infonavit;
        $expediente->save();
    }

    public function indexAutorizados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                'contratos.saldo',
                'contratos.detenido',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'creditos.credito_solic',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_infonavit',
                'lotes.calle','lotes.numero','lotes.interior'
            );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','=',NULL);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','=',NULL)
                    ->where('expedientes.gestor_id','=',Auth::user()->id);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                }
    
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        $contador = $contratos->count();

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();
                
                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }
                
                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }

        return [   
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function noAplicaInfonavit(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $expediente = Expediente::findOrFail($request->folio);
        $expediente->fecha_infonavit = "0000-01-01";
        $expediente->save();
    }

    public function indexLiquidacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    'contratos.saldo',
                    'contratos.detenido',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.precio_venta',
                    'licencias.avance as avance_lote',
                    'licencias.foto_predial',
                    'licencias.foto_lic',
                    'licencias.num_licencia',
                    'licencias.foto_acta',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'i.fecha_vigencia',
                    'creditos.credito_solic',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    'lotes.credito_puente',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    'expedientes.valor_escrituras',
                    'expedientes.fecha_ingreso',
                    'expedientes.fecha_integracion',
                    'expedientes.fecha_liquidacion',
                    'expedientes.liquidado',
                    'expedientes.infonavit',
                    'expedientes.postventa',
                    'expedientes.fovissste',
                    'expedientes.total_liquidar',
                    'expedientes.fecha_infonavit',
                    'lotes.calle','lotes.numero','lotes.interior'
                );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',0);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',0)
                    ->where('expedientes.gestor_id','=',Auth::user()->id);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where($criterio,'=',$buscar)
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)      
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)  
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)  
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id);
                        }
                    }
                }
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();
                
                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();
                
                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }
                
                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }
            }
        }

        $contador = $contratos->count();
        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function pagaresExpediente(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $totalDeposito = 0;
        $folio = $request->folio;
        $pagares = Pago_contrato::select('contrato_id','num_pago','fecha_pago','monto_pago','restante','pagado')
                    ->where('contrato_id','=',$folio)
                    ->where('pagado','!=',2)
                    ->orderBy('contrato_id','asc')
                    ->orderBy('num_pago','asc')->get();

        $calculos = Pago_contrato::select(DB::raw("SUM(monto_pago) as enganche"),
                        DB::raw("SUM(restante) as total_restante"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$folio)
                    ->get();

        return ['pagares' => $pagares,
                'calculos' => $calculos];
    }

    public function setLiquidacion(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $expediente = Expediente::findOrFail($request->folio);
            $expediente->fecha_liquidacion = $request->fecha_liquidacion;
            $expediente->valor_escrituras = $request->valor_escrituras;
            $expediente->descuento = $request->descuento;
            $expediente->notas_liquidacion = $request->notas_liquidacion;
            $expediente->obs_descuento = $request->obs_descuento;

            $contrato = Contrato::findOrFail($request->folio);
            $contrato->saldo = $contrato->saldo - round($request->descuento,2);
            $contrato->save();

            if($request->total_liquidar <= 0){
                $expediente->liquidado = 1;

                $pagaresContrato = Pago_contrato::select('id','pagado','contrato_id')
                                            ->where('contrato_id','=',$request->folio)
                                            ->where('pagado','=',1)
                                            ->orWhere('pagado','=',0)
                                            ->where('contrato_id','=',$request->folio)
                                            ->get();

                foreach ($pagaresContrato as $pagaresAnteriores){
                    $pagaresCambio = Pago_contrato::findOrFail($pagaresAnteriores->id);
                    $pagaresCambio->pagado = 3;
                    $pagaresCambio->save();
                }
            }else{
                $expediente->liquidado = 0;
            }
            $expediente->total_liquidar = $request->total_liquidar;
            $expediente->infonavit = $request->infonavit;
            $expediente->fovissste = $request->fovissste;

            if($expediente->infonavit != 0 && $expediente->fovissste == 0 ){
                $inst_seleccionada = new inst_seleccionada();
                $inst_seleccionada->credito_id = $request->folio;
                $inst_seleccionada->tipo_credito = "INFONAVIT";
                $inst_seleccionada->institucion = "INFONAVIT";
                $inst_seleccionada->monto_credito = $request->infonavit;
                $inst_seleccionada->tipo = 1;
                $inst_seleccionada->status = 2;
                $inst_seleccionada->save();

                $elegido = inst_seleccionada::select('id')
                            ->where('credito_id','=', $request->folio)
                            ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->segundo_credito = $request->infonavit;
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();
            }
            elseif($expediente->infonavit == 0 && $expediente->fovissste != 0){
                $inst_seleccionada = new inst_seleccionada();
                $inst_seleccionada->credito_id = $request->folio;
                $inst_seleccionada->tipo_credito = "FOVISSSTE";
                $inst_seleccionada->institucion = "FOVISSSTE";
                $inst_seleccionada->monto_credito = $request->fovissste;
                $inst_seleccionada->tipo = 1;
                $inst_seleccionada->status = 2;
                $inst_seleccionada->save();

                $elegido = inst_seleccionada::select('id')
                            ->where('credito_id','=', $request->folio)
                            ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->segundo_credito = $request->fovissste;
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();
            }
            else{
                $elegido = inst_seleccionada::select('id')
                ->where('credito_id','=', $request->folio)
                ->where('elegido','=',1)->get();

                $creditoEle = inst_seleccionada::findOrFail($elegido[0]->id);
                $creditoEle->monto_credito = $request->monto_credito;
                $creditoEle->save();

            }
            $expediente->save();
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function generarPagares(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $intereses = 0;

            $liquidacion = new Liquidacion();
            $liquidacion->id = $request->folio;
            $liquidacion->interes_ord = $request->intereses_ordinarios;
            $liquidacion->interes_mor = $request->intereses_moratorios;
            $liquidacion->fecha_ini_interes = $request->fecha_ini_interes;
            $liquidacion->nombre_aval = $request->nombre_aval;
            $liquidacion->direccion =  $request->direccion_aval;
            $liquidacion->telefono = $request->telefono_aval;
            $liquidacion->nombre_aval2 = $request->nombre_aval2;
            $liquidacion->direccion2 =  $request->direccion_aval2;
            $liquidacion->telefono2 = $request->telefono_aval2;
            $liquidacion->save();

            $expediente = Expediente::findOrFail($request->folio);
            $expediente->liquidado = 1;
            

            $pagares = $request->pagares;

            $pagaresContrato = Pago_contrato::select('id','pagado','contrato_id')
                                            ->where('contrato_id','=',$request->folio)
                                            ->where('pagado','=',1)
                                            ->orWhere('pagado','=',0)
                                            ->where('contrato_id','=',$request->folio)
                                            ->get();

            foreach ($pagaresContrato as $pagaresAnteriores){
                $pagaresCambio = Pago_contrato::findOrFail($pagaresAnteriores->id);
                $pagaresCambio->pagado = 3;
                $pagaresCambio->save();
            }


            foreach($pagares as $ep=>$det)
            {
                $pagos = new Pago_contrato();
                $pagos->contrato_id = $request->folio;
                $pagos->num_pago = $ep;
                $pagos->monto_pago = $det['monto_pago'];
                $pagos->fecha_pago = $det['fecha_pago'];
                $pagos->restante = $det['monto_pago'];
                $pagos->pagado = 0;
                $pagos->tipo_pagare = 1;
                $pagos->save();

                $intereses += $det['monto_pago'];

            }

            $intereses -= $expediente->total_liquidar;
            $expediente->interes_ord = round($intereses,2);

            if($expediente->interes_ord != 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $request->folio;
                $gasto->concepto = 'Interes Ordinario';
                $gasto->costo = $expediente->interes_ord;
                $gasto->fecha = $request->fecha_ini_interes;;
                $gasto->observacion = 'Intereses ordinarios al generar liquidación';
                $gasto->save();
            }


            $expediente->save();

            $contrato = Contrato::findOrFail($request->folio);
            $contrato->saldo += round($intereses,2);
            $contrato->save();
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function indexProgramacion(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                'contratos.saldo',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_liquidacion',
                'expedientes.liquidado',
                'expedientes.infonavit',
                'expedientes.fovissste',
                'expedientes.total_liquidar',
                'expedientes.fecha_infonavit',
                'expedientes.fecha_firma_esc',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'lotes.calle','lotes.numero','lotes.interior'
            );
       
        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','!=',1);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','!=',1)
                    ->where('expedientes.gestor_id','=',Auth::user()->id);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos =$query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                }
    
            }
        }

        $contratos = $contratos->orderBy('contratos.id','asc')
                                ->get();

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();
                
                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }

                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }

                
            }
        }
       
        $contador = $contratos->count();
        
        return [
            'contratos' => $contratos,
            'contador' => $contador
        ];
    }

    public function indexEnviados(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            //->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                DB::RAW("DISTINCT(contratos.id) as folio"),
                //'contratos.id as folio',
                'contratos.saldo',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_liquidacion',
                'expedientes.liquidado',
                'expedientes.infonavit',
                'expedientes.fovissste',
                'expedientes.total_liquidar',
                'expedientes.fecha_infonavit',
                'expedientes.fecha_firma_esc',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'lotes.calle','lotes.numero','lotes.interior'
                // 'avaluos.resultado','avaluos.fecha_recibido',
                // //'avaluos.id as avaluoId',
                // 'avaluos.fecha_concluido',
                // 'avaluos.pdf'
            );
       
        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','=',1);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%');
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','=',1)
                    ->where('expedientes.gestor_id','=',Auth::user()->id);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id);
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','=',1)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana);
                        }
                    }
                }
    
            }
        }

        $contratos = $contratos->distinct()
                                ->orderBy('contratos.id','asc')
                                ->paginate(10);

        if(sizeof($contratos)){
            foreach($contratos as $index => $contrato){
                $lastPagare = Pago_contrato::select('fecha_pago')->where('contrato_id','=',$contrato->folio)->orderBy('fecha_pago','desc')->get();

                $avaluos = Avaluo::select('resultado','fecha_recibido',
                                            'id as avaluoId',
                                            'fecha_concluido',
                                            'pdf')->where('contrato_id','=',$contrato->folio)->orderBy('created_at','desc')->get();
                if(sizeof($lastPagare)){
                    $contrato->ultimo_pagare = $lastPagare[0]->fecha_pago;
                }
                else{
                    $contrato->ultimo_pagare = '';
                }

                if(sizeof($avaluos)){
                    $contrato->resultado = $avaluos[0]->resultado;
                    $contrato->fecha_recibido = $avaluos[0]->fecha_recibido;
                    $contrato->avaluoId = $avaluos[0]->avaluoId;
                    $contrato->fecha_concluido = $avaluos[0]->fecha_concluido;
                    $contrato->pdf = $avaluos[0]->pdf;
                }
                else{
                    $contrato->resultado = '';
                    $contrato->fecha_recibido = '';
                    $contrato->avaluoId = '';
                    $contrato->fecha_concluido = '';
                    $contrato->pdf = '';
                }
            }
        }
       

        return [
            'contratos' => $contratos,
            'contador' => $contratos->total(),
                'pagination' => [
                    'total'         => $contratos->total(),
                    'current_page'  => $contratos->currentPage(),
                    'per_page'      => $contratos->perPage(),
                    'last_page'     => $contratos->lastPage(),
                    'from'          => $contratos->firstItem(),
                    'to'            => $contratos->lastItem(),
            ],
        ];
    }
   
    public function liquidacionPDF($id){
        $liquidacion = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
        ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
        ->join('expedientes','contratos.id','=','expedientes.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->join('licencias', 'lotes.id', '=', 'licencias.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('personal as v', 'vendedores.id', '=', 'v.id')
        ->join('personal as g', 'expedientes.gestor_id','=','g.id')
        ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
        ->leftjoin('liquidacion','expedientes.id','=','liquidacion.id')
        ->select(
            'contratos.id as folio',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
            DB::raw("CONCAT(g.nombre,' ',g.apellidos) AS nombre_gestor"),
            'c.rfc as rfc_cliente',
            'c.homoclave as homoclave_cliente',
            'c.direccion','c.colonia','c.cp','c.telefono',
            'clientes.ciudad','clientes.estado',
            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'creditos.modelo',
            'creditos.precio_venta',
            'creditos.precio_base',
                'creditos.terreno_excedente',
                'creditos.precio_terreno_excedente',
                'creditos.descuento_promocion',
                'creditos.precio_venta',
                'creditos.costo_paquete',
                'creditos.precio_obra_extra',
            'contratos.fecha_status',
            'i.tipo_credito',
            'i.institucion',
            'i.fecha_vigencia',
            'i.monto_credito as credito_solic',
            'i.cobrado',
            'i.segundo_credito',
            'contratos.avaluo_preventivo',
            'contratos.aviso_prev',
            'contratos.aviso_prev_venc',
            'contratos.saldo',
            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
            'clientes.rfc_coa as rfc_conyuge',
            'clientes.homoclave_coa as homoclave_coa',
            'clientes.coacreditado',
            'contratos.integracion',
            'lotes.fraccionamiento_id',
            'expedientes.valor_escrituras',
            'expedientes.fecha_liquidacion',
            'expedientes.liquidado',
            'expedientes.infonavit',
            'expedientes.fovissste',
            'expedientes.total_liquidar',
            'expedientes.notaria',
            'expedientes.fecha_firma_esc',
            'expedientes.interes_ord',
            'expedientes.descuento',
            'expedientes.obs_descuento',
            'expedientes.notas_liquidacion',
            'liquidacion.nombre_aval',
            'liquidacion.direccion as direccion_aval',
            'liquidacion.telefono as telefono_aval',
            'liquidacion.nombre_aval2',
            'liquidacion.direccion2 as direccion_aval2',
            'liquidacion.telefono2 as telefono_aval2',
            'lotes.calle','lotes.numero','lotes.interior',
            'lotes.ajuste','lotes.sobreprecio',
            'avaluos.resultado','avaluos.fecha_recibido'
        )
        ->where('i.elegido', '=', 1)
        ->where('i.status','=',2)
        ->where('contratos.status', '!=', 0)
        ->where('contratos.status', '!=', 2)
        ->where('expedientes.fecha_ingreso','!=',NULL)
        ->where('expedientes.valor_escrituras','!=',0)
        ->where('expedientes.fecha_infonavit','!=',NULL)
        ->where('expedientes.liquidado','=',1)
        ->where('contratos.id', '=', $id)
        ->orderBy('contratos.id','asc')
        ->get();

        $liquidacion[0]->precio_base = $liquidacion[0]->precio_base - $liquidacion[0]->descuento_promocion;
        $liquidacion[0]->precio_base = $liquidacion[0]->precio_base;

        $depositos = Pago_contrato::select('monto_pago','restante')->where('contrato_id','=',$id)->get();
        $suma= 0;
        for($i = 0; $i < count($depositos); $i++){
             $resta = $depositos[$i]->monto_pago - $depositos[$i]->restante;
             $suma = $suma + $resta;
             $liquidacion[0]->sumaDepositos = $suma;
        }

        $sumGastos = 0;
        $gastos=Gasto_admin::select('concepto','costo','id')
        ->where('contrato_id','=',$id)
        ->get();

        for($i = 0; $i < count($gastos); $i++){
            $sumGastos += $gastos[$i]->costo;
            $gastos[$i]->costo = number_format((float)$gastos[$i]->costo, 2, '.', ',');
        }

       $pagares = Pago_contrato::select('fecha_pago','restante','num_pago')->where('pagado','<',2)->where('contrato_id','=',$id)->get();
       setlocale(LC_TIME, 'es_MX.utf8');

       for($i = 0; $i < count($pagares); $i++){
        $pagares[$i]->restante1 = number_format((float)$pagares[$i]->restante, 2, '.', ',');
        $fecha3 = $pagares[$i]->fecha_pago;
        $tiempo4 = new Carbon($fecha3);
        $pagares[$i]->fecha_pago_letra = $tiempo4->formatLocalized('%d de %B de %Y');
        $pagares[$i]->montoPagoLetra = NumerosEnLetras::convertir($pagares[$i]->restante, 'Pesos', true, 'Centavos');
       }

       $totalRestante = [];
       $totalRestante = Pago_contrato::select(DB::raw("SUM(restante) as sumRestante"))
       ->groupBy('contrato_id')
       ->where('pagado','<',2)
       ->where('contrato_id','=',$id)
       ->get();

       if(sizeof($totalRestante) > 0)
            $cantRestante= $totalRestante[0]->sumRestante;
        else
            $cantRestante = 0;
             
       //$liquidacion[0]->totalRestante = $liquidacion[0]->saldo;

       $liquidacion[0]->totalRestante = 
            $liquidacion[0]->precio_venta + $liquidacion[0]->interes_ord - $liquidacion[0]->credito_solic -
            $liquidacion[0]->fovissste - $liquidacion[0]->infonavit - $liquidacion[0]->sumaDepositos - $liquidacion[0]->descuento + $sumGastos;

        $liquidacion[0]->valor_escrituras = number_format((float)$liquidacion[0]->valor_escrituras, 2, '.', ',');
        $liquidacion[0]->precio_venta = number_format((float)$liquidacion[0]->precio_venta, 2, '.', ',');
        $liquidacion[0]->interes_ord = number_format((float)$liquidacion[0]->interes_ord, 2, '.', ',');
        $liquidacion[0]->credito_solic = number_format((float)$liquidacion[0]->credito_solic, 2, '.', ',');
        $liquidacion[0]->sumaDepositos = number_format((float)$liquidacion[0]->sumaDepositos, 2, '.', ',');
        $liquidacion[0]->fovissste = number_format((float)$liquidacion[0]->fovissste, 2, '.', ',');
        $liquidacion[0]->infonavit = number_format((float)$liquidacion[0]->infonavit, 2, '.', ',');
        $liquidacion[0]->descuento = number_format((float)$liquidacion[0]->descuento, 2, '.', ',');
        $liquidacion[0]->totalRestante = number_format((float)$liquidacion[0]->totalRestante, 2, '.', ',');

        $liquidacion[0]->precio_base = number_format((float)$liquidacion[0]->precio_base, 2, '.', ',');
        $liquidacion[0]->precio_terreno_excedente = number_format((float)$liquidacion[0]->precio_terreno_excedente, 2, '.', ',');
        $liquidacion[0]->precio_obra_extra = number_format((float)$liquidacion[0]->precio_obra_extra, 2, '.', ',');
        $liquidacion[0]->sobreprecio = number_format((float)$liquidacion[0]->sobreprecio, 2, '.', ',');
        $liquidacion[0]->costo_paquete = number_format((float)$liquidacion[0]->costo_paquete, 2, '.', ',');

        if($liquidacion[0]->fecha_firma_esc != NULL){
            $fecha1 = new Carbon($liquidacion[0]->fecha_firma_esc);
            $liquidacion[0]->fecha_firma_esc = $fecha1->formatLocalized('%d de %B de %Y');
        }
        

        $fecha2 = new Carbon($liquidacion[0]->fecha_liquidacion);
        $liquidacion[0]->fecha_liquidacion = $fecha2->formatLocalized('%d de %B de %Y');



        $pdf = \PDF::loadview('pdf.contratos.liquidacion', ['liquidacion' => $liquidacion , 'gastos' => $gastos, 'pagares' => $pagares]);
        return $pdf->stream('Liquidacion.pdf');
    }

    public function updateMontoCredito(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $credito = Credito::findOrFail($request->id);
            $credito->credito_solic = $request->monto_credito;
            $credito->save();

            $inst_selec_id = inst_seleccionada::select('id')->where('credito_id','=',$request->id)->where('elegido','=',1)->where('status','=',2)->get();

            $inst_seleccionada = inst_seleccionada::findOrFail($inst_selec_id[0]->id);
            $inst_seleccionada->monto_credito = $request->monto_credito;
            $inst_seleccionada->save();
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function generarInstruccionNot(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try {
            DB::beginTransaction();
            
            $asignar = Expediente::findOrFail($request->folio);
            $asignar->fecha_firma_esc =  $request->fecha_firma_esc;
            $asignar->notaria_id =  $request->notaria_id;
            $asignar->notario =  $request->notario;
            $asignar->notaria =  $request->notaria;
            $asignar->hora_firma =  $request->hora_firma;
            $asignar->direccion_firma =  $request->direccion_firma;

            $credito = Credito::findOrFail($request->folio);
            $lote = $credito->lote_id;
            $firmado = Lote::findOrFail($lote);
            $etapa = $firmado->etapa_id;
            $cliente = $credito->prospecto_id;
            $firmado->firmado = 1;
            $firmado->save();

            $contrato = Contrato::findOrFail($request->folio);
            
            if($contrato->publicidad_id == 1){
                $bonoAnt = Bono_recomendado::select('id')->where('id','=',$request->folio)->get();

                if(sizeOf($bonoAnt)==0){
                    $bono = new BonoRecomendadoController();
                    $bono->store($request->folio, $etapa, $cliente, $contrato->fecha_status);
                }
            }
            
            $entrega = Entrega::select('id')->where('id','=',$credito->id)->get();

            if(sizeOf($entrega)){
                $asignar->postventa = 1;
            }
            $asignar->save();
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function regresarExpediente(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $folio = $request->folio;
        try{
            DB::beginTransaction();

            $contrato = Contrato::findOrFail($folio);
            $contrato->integracion = 0;
            $contrato->aviso_prev = NULL;
            $contrato->avaluo_preventivo = NULL;
            $contrato->save();

            $expediente = Expediente::findOrFail($folio);
            $expediente->delete();
            DB::commit();
                
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function excelIngresarExp(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'lotes.calle','lotes.numero','lotes.interior',
                'avaluos.resultado','avaluos.fecha_recibido',
                'avaluos.id as avaluoId','avaluos.fecha_concluido',
                'avaluos.pdf'
            );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','=',NULL)
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','=',NULL)
                    ->where('expedientes.gestor_id','=',Auth::user()->id)
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','=',NULL)
                            ->where($criterio,'=',$buscar)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','=',NULL)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                }
    
            }
        }

        return Excel::create('Expediente por Ingresar', function($excel) use ($contratos){
            $excel->sheet('Por Ingresar', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Valor de la vivienda', 'Crédito puente', 'Saldo'
                ]);
                $sheet->cells('A1:Q1', function ($cells) {
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
                $sheet->setColumnFormat(array(
                    'J' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                ));
                $cont=1;
                foreach($contratos as $index => $contrato) {
                    $cont++;

                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }
                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }
                    
                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->precio_venta,
                        $contrato->credito_puente,
                        $contrato->saldo,

                    ]);	
                }
                $num='A1:Q' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    public function excelAutorizados(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                'contratos.saldo',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'creditos.credito_solic',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_infonavit',
                'lotes.calle','lotes.numero','lotes.interior',
                'avaluos.resultado','avaluos.fecha_recibido',
                'avaluos.id as avaluoId','avaluos.fecha_concluido',
                'avaluos.pdf'
            );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','=',NULL)
                    
                    
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','=',NULL)
                    ->where('expedientes.gestor_id','=',Auth::user()->id)
                    
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','=',NULL)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','=',NULL)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                }
    
            }
        }

        $contador = $contratos->count();

        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Autorizados', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo'
                ]);

                $sheet->cells('A1:T1', function ($cells) {
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

                $sheet->setColumnFormat(array(
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }
                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }
                    
                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                    ]);	
                }
                $num='A1:T' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    public function excelLiquidacion(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
                ->join('expedientes','contratos.id','=','expedientes.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
                ->select(
                    'contratos.id as folio',
                    'contratos.saldo',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.precio_venta',
                    'licencias.avance as avance_lote',
                    'licencias.foto_predial',
                    'licencias.foto_lic',
                    'licencias.num_licencia',
                    'licencias.foto_acta',
                    'contratos.fecha_status',
                    'i.tipo_credito',
                    'i.institucion',
                    'i.fecha_vigencia',
                    'creditos.credito_solic',
                    'contratos.avaluo_preventivo',
                    'contratos.aviso_prev',
                    'contratos.aviso_prev_venc',
                    'lotes.regimen_condom',
                    'lotes.credito_puente',
                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                    DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                    DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                    'clientes.coacreditado',
                    'contratos.integracion',
                    'lotes.fraccionamiento_id',
                    'expedientes.valor_escrituras',
                    'expedientes.fecha_ingreso',
                    'expedientes.fecha_integracion',
                    'expedientes.fecha_liquidacion',
                    'expedientes.liquidado',
                    'expedientes.infonavit',
                    'expedientes.fovissste',
                    'expedientes.total_liquidar',
                    'expedientes.fecha_infonavit',
                    'lotes.calle','lotes.numero','lotes.interior',
                    'avaluos.resultado','avaluos.fecha_recibido',
                    'avaluos.id as avaluoId','avaluos.fecha_concluido',
                    'avaluos.pdf'
                );

        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',0)
                    
                    
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.nombre','like','%'. $buscar . '%')
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                }
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',0)
                    ->where('expedientes.gestor_id','=',Auth::user()->id)
                    
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',0)
                            ->where($criterio,'=',$buscar)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)      
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)  
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',0)  
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                }
            }
        }
        
        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Liquidacion', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo','Inscripción Infonavit'
                ]);

                $sheet->cells('A1:T1', function ($cells) {
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

                $sheet->setColumnFormat(array(
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }

                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    if($contrato->fecha_infonavit=='0000-01-01' || $contrato->fecha_infonavit == NULL){
                        $contrato->fecha_infonavit='No aplica';
                    }
                    else{
                        $fecha3 = new Carbon($contrato->fecha_infonavit);
                        $contrato->fecha_infonavit = $fecha3->formatLocalized('%d de %B de %Y');
                    }
                    
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');
                   
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                        $contrato->fecha_infonavit,
                    ]);	
                }
                $num='A1:T' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

    public function excelProgramacion(Request $request)
    {
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $contador = 0;
        $rolId = Auth::user()->rol_id;

        $query = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->leftJoin('avaluos','contratos.id','=','avaluos.contrato_id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->join('personal as v', 'vendedores.id', '=', 'v.id')
            ->join('inst_seleccionadas as i', 'creditos.id', '=', 'i.credito_id')
            ->select(
                'contratos.id as folio',
                'contratos.saldo',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.precio_venta',
                'licencias.avance as avance_lote',
                'licencias.foto_predial',
                'licencias.foto_lic',
                'licencias.num_licencia',
                'licencias.foto_acta',
                'contratos.fecha_status',
                'i.tipo_credito',
                'i.institucion',
                'i.fecha_vigencia',
                'i.monto_credito as credito_solic',
                'i.cobrado',
                'i.segundo_credito',
                'contratos.avaluo_preventivo',
                'contratos.aviso_prev',
                'contratos.aviso_prev_venc',
                'contratos.saldo',
                'lotes.regimen_condom',
                'lotes.credito_puente',
                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),
                DB::raw('DATEDIFF(current_date,contratos.aviso_prev_venc) as diferencia'),
                DB::raw('DATEDIFF(current_date,i.fecha_vigencia) as vigencia'),
                'clientes.coacreditado',
                'contratos.integracion',
                'lotes.fraccionamiento_id',
                'expedientes.valor_escrituras',
                'expedientes.fecha_ingreso',
                'expedientes.fecha_integracion',
                'expedientes.fecha_liquidacion',
                'expedientes.liquidado',
                'expedientes.infonavit',
                'expedientes.fovissste',
                'expedientes.total_liquidar',
                'expedientes.fecha_infonavit',
                'expedientes.fecha_firma_esc',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'expedientes.notaria_id',
                'expedientes.notario',
                'expedientes.notaria',
                'expedientes.hora_firma',
                'expedientes.direccion_firma',
                'lotes.calle','lotes.numero','lotes.interior',
                'avaluos.resultado','avaluos.fecha_recibido',
                'avaluos.id as avaluoId','avaluos.fecha_concluido',
                'avaluos.pdf'
            );
       
        if($rolId == 1 || $rolId == 4 || $rolId == 6 || Auth::user()->id == 24701){
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','!=',1)
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                    case 'expedientes.gestor_id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                }
    
            }
        }else{
            if ($buscar == ''){
                $contratos = $query
                    ->where('i.elegido', '=', 1)
                    ->where('i.status','=',2)
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('expedientes.fecha_ingreso','!=',NULL)
                    ->where('expedientes.valor_escrituras','!=',0)
                    ->where('expedientes.fecha_infonavit','!=',NULL)
                    ->where('expedientes.liquidado','=',1)
                    ->where('expedientes.postventa','!=',1)
                    ->where('expedientes.gestor_id','=',Auth::user()->id)
                    ->orderBy('contratos.id','asc')
                    ->get();
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.nombre','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
    
                            ->orWhere('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('c.apellidos','like','%'. $buscar . '%')
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'contratos.id':{
                        $contratos = $query
                            ->where('i.elegido', '=', 1)
                            ->where('i.status','=',2)
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('expedientes.fecha_ingreso','!=',NULL)
                            ->where('expedientes.valor_escrituras','!=',0)
                            ->where('expedientes.fecha_infonavit','!=',NULL)
                            ->where('expedientes.liquidado','=',1)
                            ->where('expedientes.postventa','!=',1)
                            ->where('expedientes.gestor_id','=',Auth::user()->id)
                            ->where($criterio,'=',$buscar)
                            ->orderBy('contratos.id','asc')
                            ->get();
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($b_etapa == '' && $b_manzana =='' && $b_lote == '' ){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                            $contratos =$query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote == ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                        elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                            $contratos = $query
                                ->where('i.elegido', '=', 1)
                                ->where('i.status','=',2)
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('expedientes.fecha_ingreso','!=',NULL)
                                ->where('expedientes.valor_escrituras','!=',0)
                                ->where('expedientes.fecha_infonavit','!=',NULL)
                                ->where('expedientes.liquidado','=',1)
                                ->where('expedientes.postventa','!=',1)
                                ->where('expedientes.gestor_id','=',Auth::user()->id)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', '=', $b_manzana)
                                ->orderBy('contratos.id','asc')
                                ->get();
                        }
                    }
                }
    
            }
        }

        return Excel::create('Expediente', function($excel) use ($contratos){
            $excel->sheet('Programación de firma', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Asesor', 'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Dirección',
                    'Avance obra', 'Firma contrato', 'Resultado avaluo', 'Aviso preventivo',
                    'Tipo de crédito', 'Institución de financiamiento', 'Monto autorizado', 'Fecha vigencia',
                    'Valor de la vivienda', 'Valor a escriturar','Crédito puente', 'Saldo','Inscripción Infonavit','Liquidación',
                    'Firma de escrituras'
                ]);

                $sheet->cells('A1:W1', function ($cells) {
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

                $sheet->setColumnFormat(array(
                    'K' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'T' => '$#,##0.00',
                ));

                $cont=1;

                foreach($contratos as $index => $contrato) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    if($contrato->avaluo_preventivo=='0000-01-01' || $contrato->avaluo_preventivo == NULL){
                        $contrato->resultado = 'No aplica';
                    }

                    if($contrato->aviso_prev=='0000-01-01' || $contrato->aviso_prev == NULL){
                        $contrato->aviso_prev = 'No aplica';
                    }
                    else{
                        $fecha2 = new Carbon($contrato->aviso_prev);
                        $contrato->aviso_prev = $fecha2->formatLocalized('%d de %B de %Y');
                    }

                    if($contrato->fecha_infonavit=='0000-01-01' || $contrato->fecha_infonavit == NULL){
                        $contrato->fecha_infonavit='No aplica';
                    }
                    else{
                        $fecha3 = new Carbon($contrato->fecha_infonavit);
                        $contrato->fecha_infonavit = $fecha3->formatLocalized('%d de %B de %Y');
                    }
                    if($contrato->fecha_firma_esc==null){
                        $contrato->fecha_firma_esc = 'Sin firmar';
                    }
                    else{
                        $fecha5 = new Carbon($contrato->fecha_firma_esc);
                        $contrato->fecha_firma_esc = $fecha5->formatLocalized('%d de %B de %Y');
                    }
                    
                    $fecha1 = new Carbon($contrato->fecha_status);
                    $contrato->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $fecha4 = new Carbon($contrato->fecha_liquidacion);
                    $contrato->fecha_liquidacion = $fecha4->formatLocalized('%d de %B de %Y');
                   
                    $sheet->row($index+2, [
                        $contrato->folio, 
                        $contrato->nombre_cliente,
                        $contrato->nombre_vendedor,
                        $contrato->proyecto,
                        $contrato->etapa,
                        $contrato->manzana,
                        $contrato->num_lote,
                        $contrato->calle.' '.$contrato->numero,
                        $contrato->avance_lote,
                        $contrato->fecha_status,
                        $contrato->resultado,
                        $contrato->aviso_prev,
                        $contrato->tipo_credito,
                        $contrato->institucion,
                        $contrato->credito_solic,
                        $contrato->fecha_vigencia,
                        $contrato->precio_venta,
                        $contrato->valor_escrituras,
                        $contrato->credito_puente,
                        $contrato->saldo,
                        $contrato->fecha_infonavit,
                        $contrato->fecha_liquidacion,
                        $contrato->fecha_firma_esc,
                    ]);	
                }
                $num='A1:W' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
       
    }

}
