<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Obs_entrega;
use Auth;
use App\Entrega;
use Carbon\Carbon;
use App\Revision_previa;
use App\Detalle_previo;

class RevisionPreviaController extends Controller
{
    public function indexSinRevision(Request $request){
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $fecha = Carbon::now();
        $mytime = $fecha->toTimeString();
        $hoy =  $fecha->toDateString();

        if($buscar == ''){
            $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'etapas.carta_bienvenida',

                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        
                        'licencias.avance as avance_lote',
                        'licencias.visita_avaluo',
                        'licencias.foto_predial',
                        'licencias.foto_lic',
                        'licencias.num_licencia',
                        'contratos.fecha_status',
                        'contratos.status',
                        'contratos.equipamiento',
                        'expedientes.fecha_firma_esc',
                        'lotes.fecha_entrega_obra',
                        'lotes.id as loteId',
                        'entregas.revision_previa',
                        'entregas.fecha_program',
                        
                        DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where('entregas.fecha_program','!=',NULL)
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'etapas.carta_bienvenida',

                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        
                        'licencias.avance as avance_lote',
                        'licencias.visita_avaluo',
                        'licencias.foto_predial',
                        'licencias.foto_lic',
                        'licencias.num_licencia',
                        'contratos.fecha_status',
                        'contratos.status',
                        'contratos.equipamiento',
                        'expedientes.fecha_firma_esc',
                        'lotes.fecha_entrega_obra',
                        'lotes.id as loteId',
                        'entregas.revision_previa',
                        'entregas.fecha_program',
                        
                        DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where('entregas.fecha_program','!=',NULL)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->paginate(8);

                    break;
                }

                case 'entregas.fecha_program':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'etapas.carta_bienvenida',

                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        
                        'licencias.avance as avance_lote',
                        'licencias.visita_avaluo',
                        'licencias.foto_predial',
                        'licencias.foto_lic',
                        'licencias.num_licencia',
                        'contratos.fecha_status',
                        'contratos.status',
                        'contratos.equipamiento',
                        'expedientes.fecha_firma_esc',
                        'lotes.fecha_entrega_obra',
                        'lotes.id as loteId',
                        'entregas.revision_previa',
                        'entregas.fecha_program',
                        
                        DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where('entregas.fecha_program','!=',NULL)
                    ->whereBetween($criterio, [$buscar, $b_etapa])
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->paginate(8);

                    break;
                }

                case 'contratos.id':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'etapas.carta_bienvenida',

                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        
                        'licencias.avance as avance_lote',
                        'licencias.visita_avaluo',
                        'licencias.foto_predial',
                        'licencias.foto_lic',
                        'licencias.num_licencia',
                        'contratos.fecha_status',
                        'contratos.status',
                        'contratos.equipamiento',
                        'expedientes.fecha_firma_esc',
                        'lotes.fecha_entrega_obra',
                        'lotes.id as loteId',
                        'entregas.revision_previa',
                        'entregas.fecha_program',
                        
                        DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where('entregas.fecha_program','!=',NULL)
                    ->where($criterio, '=', $buscar)
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->paginate(8);

                    break;
                }

                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);

                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);

                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);

                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);

                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                        ->join('licencias', 'lotes.id', '=', 'licencias.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as c', 'clientes.id', '=', 'c.id')
                        ->select('contratos.id as folio', 
                            'contratos.equipamiento',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'etapas.carta_bienvenida',

                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'licencias.avance as avance_lote',
                            'licencias.visita_avaluo',
                            'licencias.foto_predial',
                            'licencias.foto_lic',
                            'licencias.num_licencia',
                            'contratos.fecha_status',
                            'contratos.status',
                            'contratos.equipamiento',
                            'expedientes.fecha_firma_esc',
                            'lotes.fecha_entrega_obra',
                            'lotes.id as loteId',
                            'entregas.revision_previa',
                            'entregas.fecha_program',
                            
                            DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                        ->orderBy('licencias.avance','desc')
                        ->orderBy('lotes.fecha_entrega_obra','desc')
                        ->paginate(8);

                    }

                    break;
                }
            }
        }

                return [
                    'pagination' => [
                        'total'         => $contratos->total(),
                        'current_page'  => $contratos->currentPage(),
                        'per_page'      => $contratos->perPage(),
                        'last_page'     => $contratos->lastPage(),
                        'from'          => $contratos->firstItem(),
                        'to'            => $contratos->lastItem(),
                    ],'contratos' => $contratos, 'hora' => $mytime, 'hoy' => $hoy,
                ];
    }
}
