<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;

class ExpedienteController extends Controller
{
    public function indexContratos (Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        

        if($buscar == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
            ->join('personal as c','clientes.id','=','c.id')
            ->join('personal as v','vendedores.id','=','v.id')
            ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
            ->select('contratos.id as folio',
                     DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                     DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                      'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                      'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                      'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                     DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                     'contratos.integracion','lotes.fraccionamiento_id')
            ->where('i.elegido','=',1)
            ->where('contratos.integracion','=',0)
            ->where('contratos.status','!=',0)
            ->where('contratos.status','!=',2)
            ->paginate(8);
        }else{
           if($criterio != 'lotes.fraccionamiento_id' && $criterio != 'c.nombre' && $criterio != 'v.nombre'  ){  
            $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
            ->join('personal as c','clientes.id','=','c.id')
            ->join('personal as v','vendedores.id','=','v.id')
            ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
            ->select('contratos.id as folio',
                     DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                     DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                      'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                      'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                      'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                     DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                     'contratos.integracion','lotes.fraccionamiento_id')
            ->where('i.elegido','=',1)
            ->where('contratos.integracion','=',0)
            ->where('contratos.status','!=',0)
            ->where('contratos.status','!=',2)
            ->where($criterio,'like', '%'. $buscar . '%')
            ->paginate(8);
        }else{

            if($criterio=='lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote == ''){
                $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                ->join('personal as c','clientes.id','=','c.id')
                ->join('personal as v','vendedores.id','=','v.id')
                ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                ->select('contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                            'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                            'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                            'contratos.integracion','lotes.fraccionamiento_id')
                ->where('i.elegido','=',1)
                ->where('contratos.integracion','=',0)
                ->where('contratos.status','!=',0)
                ->where('contratos.status','!=',2)
                ->where($criterio,'=',$buscar)
                ->paginate(8);
    }else{
        if($criterio=='lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote == ''){
            $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
            ->join('personal as c','clientes.id','=','c.id')
            ->join('personal as v','vendedores.id','=','v.id')
            ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
            ->select('contratos.id as folio',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                        'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                        'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                        'contratos.integracion','lotes.fraccionamiento_id')
            ->where('i.elegido','=',1)
            ->where('contratos.integracion','=',0)
            ->where('contratos.status','!=',0)
            ->where('contratos.status','!=',2)
            ->where($criterio,'=',$buscar)
            ->where('lotes.etapa_id','=',$b_etapa)
            ->paginate(8);
        }else{
            if($criterio=='lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana != '' && $b_lote == ''){
                $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                ->join('personal as c','clientes.id','=','c.id')
                ->join('personal as v','vendedores.id','=','v.id')
                ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                ->select('contratos.id as folio',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                            'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                            'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                            'contratos.integracion','lotes.fraccionamiento_id')
                ->where('i.elegido','=',1)
                ->where('contratos.integracion','=',0)
                ->where('contratos.status','!=',0)
                ->where('contratos.status','!=',2)
                ->where($criterio,'=',$buscar)
                ->where('creditos.manzana','like', '%'. $b_manzana . '%')
                ->paginate(8);
            }else{
                if($criterio=='lotes.fraccionamiento_id' && $b_etapa == ''  && $b_manzana == '' && $b_lote != ''){
                    $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                    ->join('personal as c','clientes.id','=','c.id')
                    ->join('personal as v','vendedores.id','=','v.id')
                    ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                    ->select('contratos.id as folio',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                'contratos.integracion','lotes.fraccionamiento_id')
                    ->where('i.elegido','=',1)
                    ->where('contratos.integracion','=',0)
                    ->where('contratos.status','!=',0)
                    ->where('contratos.status','!=',2)
                    ->where($criterio,'=',$buscar)
                    ->where('creditos.num_lote','like', '%'. $b_lote . '%')
                    ->paginate(8);
                }else{
                    if($criterio=='lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                        ->join('personal as c','clientes.id','=','c.id')
                        ->join('personal as v','vendedores.id','=','v.id')
                        ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                        ->select('contratos.id as folio',
                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                    'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                    'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                    'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                    'contratos.integracion','lotes.fraccionamiento_id')
                        ->where('i.elegido','=',1)
                        ->where('contratos.integracion','=',0)
                        ->where('contratos.status','!=',0)
                        ->where('contratos.status','!=',2)
                        ->where($criterio,'=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('creditos.manzana','like', '%'. $b_manzana . '%')
                        ->paginate(8);
                    }else{
                        if($criterio=='lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana != '' && $b_lote != ''){
                            $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('clientes','creditos.prospecto_id','=','clientes.id')
                            ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                            ->join('personal as c','clientes.id','=','c.id')
                            ->join('personal as v','vendedores.id','=','v.id')
                            ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                            ->select('contratos.id as folio',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                        'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                        'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                        'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                        DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                        'contratos.integracion','lotes.fraccionamiento_id')
                            ->where('i.elegido','=',1)
                            ->where('contratos.integracion','=',0)
                            ->where('contratos.status','!=',0)
                            ->where('contratos.status','!=',2)
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$b_etapa)
                            ->where('creditos.manzana','like', '%'. $b_manzana . '%')
                            ->where('creditos.num_lote','like', '%'. $b_lote . '%')
                            ->paginate(8);
                        }else{
                            if($criterio=='lotes.fraccionamiento_id' && $b_etapa != ''  && $b_manzana == '' && $b_lote != ''){
                                $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                                ->join('personal as c','clientes.id','=','c.id')
                                ->join('personal as v','vendedores.id','=','v.id')
                                ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                                ->select('contratos.id as folio',
                                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                            'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                            'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                            'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                            DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                            'contratos.integracion','lotes.fraccionamiento_id')
                                ->where('i.elegido','=',1)
                                ->where('contratos.integracion','=',0)
                                ->where('contratos.status','!=',0)
                                ->where('contratos.status','!=',2)
                                ->where($criterio,'=',$buscar)
                                ->where('lotes.etapa_id','=',$b_etapa)
                                ->where('creditos.num_lote','like', '%'. $b_lote . '%')
                                ->paginate(8);
                            }else{
                                if($criterio=='c.nombre' && $buscar !='' ){
                                    $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                                    ->join('lotes','creditos.lote_id','=','lotes.id')
                                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                    ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                                    ->join('personal as c','clientes.id','=','c.id')
                                    ->join('personal as v','vendedores.id','=','v.id')
                                    ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                                    ->select('contratos.id as folio',
                                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                                'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                                'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                                DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                                'contratos.integracion','lotes.fraccionamiento_id')
                                    ->where('i.elegido','=',1)
                                    ->where('contratos.integracion','=',0)
                                    ->where('contratos.status','!=',0)
                                    ->where('contratos.status','!=',2)
                                    ->where($criterio, 'like', '%'. $buscar . '%')
                                    ->orWhere('c.apellidos', 'like', '%'. $buscar . '%')
                                    ->where('i.elegido','=',1)
                                    ->where('contratos.integracion','=',0)
                                    ->where('contratos.status','!=',0)
                                    ->where('contratos.status','!=',2)
                                    ->paginate(8);
                                }else{
                                    if($criterio=='v.nombre' && $buscar !='' ){
                                        $contratos = Contrato::join('creditos', 'contratos.id', '=','creditos.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                                        ->join('vendedores','clientes.vendedor_id','=','vendedores.id')
                                        ->join('personal as c','clientes.id','=','c.id')
                                        ->join('personal as v','vendedores.id','=','v.id')
                                        ->join('inst_seleccionadas as i','creditos.id','=','i.credito_id')
                                        ->select('contratos.id as folio',
                                                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                                    'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana',
                                                    'creditos.num_lote','contratos.avance_lote','contratos.fecha_status','i.tipo_credito',
                                                    'i.institucion','contratos.avaluo_preventivo','contratos.aviso_prev','lotes.regimen_condom',
                                                    DB::raw("CONCAT(clientes.nombre_coa,' ',clientes.apellidos_coa) AS nombre_conyuge"),'clientes.coacreditado',
                                                    'contratos.integracion','lotes.fraccionamiento_id')
                                        ->where('i.elegido','=',1)
                                        ->where('contratos.integracion','=',0)
                                        ->where('contratos.status','!=',0)
                                        ->where('contratos.status','!=',2)
                                        ->where($criterio, 'like', '%'. $buscar . '%')
                                        ->orWhere('v.apellidos', 'like', '%'. $buscar . '%')
                                        ->where('i.elegido','=',1)
                                        ->where('contratos.integracion','=',0)
                                        ->where('contratos.status','!=',0)
                                        ->where('contratos.status','!=',2)
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
}
