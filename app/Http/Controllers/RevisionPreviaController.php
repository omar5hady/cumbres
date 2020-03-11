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
use App\Solic_equipamiento;
use App\Ini_obra;

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

        $query = Entrega::join('contratos','entregas.id','contratos.id')
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
            );

        if($buscar == ''){
            $contratos = $query
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
                    $contratos = $query
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
                    $contratos = $query
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
                    $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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
                        $contratos = $query
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

    public function indexSinRevisionContratista(Request $request){
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $fecha = Carbon::now();
        $mytime = $fecha->toTimeString();
        $hoy =  $fecha->toDateString();

        $query = Entrega::join('contratos','entregas.id', '=','contratos.id')
            ->join('revisiones_previas','revisiones_previas.id', '=','contratos.id')
            ->join('expedientes','contratos.id','=','expedientes.id')
            ->join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->select('contratos.id as folio', 
                'contratos.equipamiento',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                
                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                
                'contratos.fecha_status',
                'contratos.status',
                'contratos.equipamiento',
                'expedientes.fecha_firma_esc',
                'lotes.fecha_entrega_obra',
                'lotes.id as loteId',
                'entregas.revision_previa',
                'entregas.fecha_program',
                'revisiones_previas.id_contratista',
                
                DB::raw('DATEDIFF(current_date,entregas.fecha_program) as diferencia')
        );

        if($buscar == ''){
            $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('entregas.fecha_program','!=',NULL)
                    ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                    ->where('entregas.revision_previa','!=',0)
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                        ->where('entregas.revision_previa','!=',0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                        ->orderBy('entregas.revision_previa','asc')
                        ->orderBy('entregas.fecha_program','asc')
                        ->paginate(8);

                    break;
                }

                case 'entregas.fecha_program':{
                    $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                        ->where('entregas.revision_previa','!=',0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->whereBetween($criterio, [$buscar, $b_etapa])
                        ->orderBy('entregas.revision_previa','asc')
                        ->orderBy('entregas.fecha_program','asc')
                        ->paginate(8);

                    break;
                }

                case 'contratos.id':{
                    $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                        ->where('entregas.revision_previa','!=',0)
                        ->where('entregas.fecha_program','!=',NULL)
                        ->where($criterio, '=', $buscar)
                        ->orderBy('entregas.revision_previa','asc')
                        ->orderBy('entregas.fecha_program','asc')
                        ->paginate(8);

                    break;
                }

                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);

                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
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
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.etapa_id', '=', $b_etapa)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);
                        }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->where('lotes.manzana', 'like', '%'. $b_manzana . '%')
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);

                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
                            ->where('entregas.fecha_program','!=',NULL)
                            ->where($criterio, '=', $buscar)
                            ->where('lotes.num_lote', '=', $b_lote)
                            ->orderBy('licencias.avance','desc')
                            ->orderBy('lotes.fecha_entrega_obra','desc')
                            ->paginate(8);

                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                            ->where('contratos.status', '!=', 0)
                            ->where('contratos.status', '!=', 2)
                            ->where('revisiones_previas.id_contratista','=',Auth::user()->id)
                            ->where('entregas.revision_previa','!=',0)
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

    public function storeRevision(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $datosLote = Entrega::join('contratos','entregas.id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 'creditos.modelo','creditos.fraccionamiento',
                        'creditos.etapa','creditos.manzana','creditos.num_lote',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'c.celular', 
                        DB::raw("CONCAT(lotes.calle,' Num.',lotes.numero) AS direccion"),
                        'lotes.manzana', 'lotes.aviso','lotes.id as lote_id',
                        DB::raw('DATEDIFF(current_date,entregas.fecha_entrega_real) as diferencia'
                        )
                    )
                    ->where('contratos.id','=',$request->folio)->get();

        $datosContratista = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                    ->select('contratistas.id','contratistas.nombre')
                    ->where('ini_obras.clave','=',$datosLote[0]->aviso)->get();

        $observacion = $request->observacion;
        $folio = $request->folio;
        $diferencia = $request->diferencia;
        $id_contratista = $datosContratista[0]->id;

        ///////// COCHERA ////////////
            $mona_cochera = $request->mona_cochera;
            $centro_carga_cochera = $request->centro_carga_cochera;
            $cuadro_hidraulico_cochera = $request->cuadro_hidraulico_cochera;
            $interfon_cochera = $request->interfon_cochera;
            $cisterna_cochera = $request->cisterna_cochera;
            $bomba_cochera = $request->bomba_cochera;
            $tapa_cisterna_cochera = $request->tapa_cisterna_cochera;
            $tapa_registro_cochera = $request->tapa_registro_cochera;
            $acc_electrico_cochera = $request->acc_electrico_cochera;
            $acabado_muros_cochera = $request->acabado_muros_cochera;
            $acabado_plafon_cochera = $request->acabado_plafon_cochera;
            $piso_cochera = $request->piso_cochera;
            $zoclo_cochera = $request->zoclo_cochera;
            //////// Observacion /////////
            $mona_cochera_obs = $request->mona_cochera_obs;
            $centro_carga_cochera_obs = $request->centro_carga_cochera_obs;
            $cuadro_hidraulico_cochera_obs = $request->cuadro_hidraulico_cochera_obs;
            $interfon_cochera_obs = $request->interfon_cochera_obs;
            $cisterna_cochera_obs = $request->cisterna_cochera_obs;
            $bomba_cochera_obs = $request->bomba_cochera_obs;
            $tapa_cisterna_cochera_obs = $request->tapa_cisterna_cochera_obs;
            $tapa_registro_cochera_obs = $request->tapa_registro_cochera_obs;
            $acc_electrico_cochera_obs = $request->acc_electrico_cochera_obs;
            $acabado_muros_cochera_obs = $request->acabado_muros_cochera_obs;
            $acabado_plafon_cochera_obs = $request->acabado_plafon_cochera_obs;
            $piso_cochera_obs = $request->piso_cochera_obs;
            $zoclo_cochera_obs = $request->zoclo_cochera_obs;

        ///////// SALA COMEDOR /////////
            $puerta_pric_sala = $request->puerta_pric_sala;
            $chapa_sala = $request->chapa_sala;
            $sello_marco_sala = $request->sello_marco_sala;
            $ventana_sala = $request->ventana_sala;
            $sello_ventana_sala = $request->sello_ventana_sala;
            $vidrio_ventana_sala = $request->vidrio_ventana_sala;
            $mosquitero_sala = $request->mosquitero_sala;
            $cancel_sala = $request->cancel_sala;
            $sello_cancel_sala = $request->sello_cancel_sala;
            $vidrio_cancel_sala = $request->vidrio_cancel_sala;
            $salida_alarma_sala = $request->salida_alarma_sala;
            $acc_electrico_sala = $request->acc_electrico_sala;
            $acabado_muros_sala = $request->acabado_muros_sala;
            $acabado_plafon_sala = $request->acabado_plafon_sala;
            $piso_sala = $request->piso_sala;
            $zoclo_sala = $request->zoclo_sala;
            ////// Observacion ///////
            $puerta_pric_sala_obs = $request->puerta_pric_sala_obs;
            $chapa_sala_obs = $request->chapa_sala_obs;
            $sello_marco_sala_obs = $request->sello_marco_sala_obs;
            $ventana_sala_obs = $request->ventana_sala_obs;
            $sello_ventana_sala_obs = $request->sello_ventana_sala_obs;
            $vidrio_ventana_sala_obs = $request->vidrio_ventana_sala_obs;
            $mosquitero_sala_obs = $request->mosquitero_sala_obs;
            $cancel_sala_obs = $request->cancel_sala_obs;
            $sello_cancel_sala_obs = $request->sello_cancel_sala_obs;
            $vidrio_cancel_sala_obs = $request->vidrio_cancel_sala_obs;
            $salida_alarma_sala_obs = $request->salida_alarma_sala_obs;
            $acc_electrico_sala_obs = $request->acc_electrico_sala_obs;
            $acabado_muros_sala_obs = $request->acabado_muros_sala_obs;
            $acabado_plafon_sala_obs = $request->acabado_plafon_sala_obs;
            $piso_sala_obs = $request->piso_sala_obs;
            $zoclo_sala_obs = $request->zoclo_sala_obs;
        
        ///////////////// COCINA //////////////
            $tarja_cocina = $request->tarja_cocina;
            $puerta_cocina = $request->puerta_cocina;
            $chapa_cocina = $request->chapa_cocina;
            $sello_pv_cocina = $request->sello_pv_cocina;
            $vidrio_pv_cocina = $request->vidrio_pv_cocina;
            $mosquitero_cocina = $request->mosquitero_cocina;
            $salida_alarma_cocina = $request->salida_alarma_cocina;
            $interfon_cocina = $request->interfon_cocina;
            $acc_electrico_cocina = $request->acc_electrico_cocina;
            $centro_carga_cocina = $request->centro_carga_cocina;
            $inst_gas_cocina = $request->inst_gas_cocina;
            $inst_refrigerador_cocina = $request->inst_refrigerador_cocina;
            $barra_cocina = $request->barra_cocina;
            $azulejo_cocina = $request->azulejo_cocina;
            $acabado_muro_cocina = $request->acabado_muro_cocina;
            $acabado_plafon_cocina = $request->acabado_plafon_cocina;
            $piso_cocina = $request->piso_cocina;
            $zoclo_cocina = $request->zoclo_cocina;
            ///////// Observaciones ////////////
            $tarja_cocina_obs = $request->tarja_cocina_obs;
            $puerta_cocina_obs = $request->puerta_cocina_obs;
            $chapa_cocina_obs = $request->chapa_cocina_obs;
            $sello_pv_cocina_obs = $request->sello_pv_cocina_obs;
            $vidrio_pv_cocina_obs = $request->vidrio_pv_cocina_obs;
            $mosquitero_cocina_obs = $request->mosquitero_cocina_obs;
            $salida_alarma_cocina_obs = $request->salida_alarma_cocina_obs;
            $interfon_cocina_obs = $request->interfon_cocina_obs;
            $acc_electrico_cocina_obs = $request->acc_electrico_cocina_obs;
            $centro_carga_cocina_obs = $request->centro_carga_cocina_obs;
            $inst_gas_cocina_obs = $request->inst_gas_cocina_obs;
            $inst_refrigerador_cocina_obs = $request->inst_refrigerador_cocina_obs;
            $barra_cocina_obs = $request->barra_cocina_obs;
            $azulejo_cocina_obs = $request->azulejo_cocina_obs;
            $acabado_muro_cocina_obs = $request->acabado_muro_cocina_obs;
            $acabado_plafon_cocina_obs = $request->acabado_plafon_cocina_obs;
            $piso_cocina_obs = $request->piso_cocina_obs;
            $zoclo_cocina_obs = $request->zoclo_cocina_obs;
        //////////  MEDIO BAÑO  /////////////
            $puerta_mb = $request->puerta_mb; 
            $chapa_mb = $request->chapa_mb;
            $barra_lavabo_mb = $request->barra_lavabo_mb;
            $lavabo_mb = $request->lavabo_mb;
            $monomando_mb = $request->monomando_mb;
            $wc_mb = $request->wc_mb;
            $acc_bano_mb = $request->acc_bano_mb;
            $acc_electrico_mb = $request->acc_electrico_mb;
            $ventana_mb = $request->ventana_mb;
            $sello_ventana_mb = $request->sello_ventana_mb;
            $vidrio_mb = $request->vidrio_mb;
            $mosquitero_mb = $request->mosquitero_mb;
            $acabado_muro_mb = $request->acabado_muro_mb;
            $acabado_plafon_mb = $request->acabado_plafon_mb;
            $piso_mb = $request->piso_mb;
            $zoclo_mb = $request->zoclo_mb;
            ////////// Observacion //////////
            $puerta_mb_obs = $request->puerta_mb_obs; 
            $chapa_mb_obs = $request->chapa_mb_obs;
            $barra_lavabo_mb_obs = $request->barra_lavabo_mb_obs;
            $lavabo_mb_obs = $request->lavabo_mb_obs;
            $monomando_mb_obs = $request->monomando_mb_obs;
            $wc_mb_obs = $request->wc_mb_obs;
            $acc_bano_mb_obs = $request->acc_bano_mb_obs;
            $acc_electrico_mb_obs = $request->acc_electrico_mb_obs;
            $ventana_mb_obs = $request->ventana_mb_obs;
            $sello_ventana_mb_obs = $request->sello_ventana_mb_obs;
            $vidrio_mb_obs = $request->vidrio_mb_obs;
            $mosquitero_mb_obs = $request->mosquitero_mb_obs;
            $acabado_muro_mb_obs = $request->acabado_muro_mb_obs;
            $acabado_plafon_mb_obs = $request->acabado_plafon_mb_obs;
            $piso_mb_obs = $request->piso_mb_obs;
            $zoclo_mb_obs = $request->zoclo_mb_obs;

        /////////////  PATIO  /////////////////
            $calentador_patio = $request->calentador_patio; 
            $inst_gas_patio = $request->inst_gas_patio; 
            $acc_electrico_patio = $request->acc_electrico_patio; 
            $lavadero_patio = $request->lavadero_patio; 
            $llaves_nariz_patio = $request->llaves_nariz_patio; 
            $descarga_lavadora_patio = $request->descarga_lavadora_patio; 
            $coladera_patio = $request->coladera_patio; 
            $tapa_registro_patio = $request->tapa_registro_patio; 
            $escalera_marina_patio = $request->escalera_marina_patio; 
            $techumbre_patio = $request->techumbre_patio; 
            $firme_cilindros_patio = $request->firme_cilindros_patio; 
            $rodapie_patio = $request->rodapie_patio; 
            $acabado_muros_patio = $request->acabado_muros_patio; 
            $acabado_volado_patio = $request->acabado_volado_patio; 
            $piso_patio = $request->piso_patio; 
            $zoclo_patio = $request->zoclo_patio; 
            ///////// Observacion //////////////
            $calentador_patio_obs = $request->calentador_patio_obs; 
            $inst_gas_patio_obs = $request->inst_gas_patio_obs; 
            $acc_electrico_patio_obs = $request->acc_electrico_patio_obs; 
            $lavadero_patio_obs = $request->lavadero_patio_obs; 
            $llaves_nariz_patio_obs = $request->llaves_nariz_patio_obs; 
            $descarga_lavadora_patio_obs = $request->descarga_lavadora_patio_obs; 
            $coladera_patio_obs = $request->coladera_patio_obs; 
            $tapa_registro_patio_obs = $request->tapa_registro_patio_obs; 
            $escalera_marina_patio_obs = $request->escalera_marina_patio_obs; 
            $techumbre_patio_obs = $request->techumbre_patio_obs; 
            $firme_cilindros_patio_obs = $request->firme_cilindros_patio_obs; 
            $rodapie_patio_obs = $request->rodapie_patio_obs; 
            $acabado_muros_patio_obs = $request->acabado_muros_patio_obs; 
            $acabado_volado_patio_obs = $request->acabado_volado_patio_obs; 
            $piso_patio_obs = $request->piso_patio_obs; 
            $zoclo_patio_obs = $request->zoclo_patio_obs;
        
        /////////////// ESCALERA ////////////////
            $nicho_escalera = $request->nicho_escalera; 
            $escalones_escalera = $request->escalones_escalera;
            $piso_escalera = $request->piso_escalera;
            $zoclo_escalera = $request->zoclo_escalera;
            $barandal_escalera = $request->barandal_escalera;
            $pasamanos_escalera = $request->pasamanos_escalera;
            $sardinel_escalera = $request->sardinel_escalera;
            $macetero_escalera = $request->macetero_escalera;
            $cajillos_escalera = $request->cajillos_escalera;
            $acc_electricos_escalera = $request->acc_electricos_escalera;
            $acabado_muro_escalera = $request->acabado_muro_escalera;
            $acabado_plafon_escalera = $request->acabado_plafon_escalera;
            ///////////// Observacion //////////////
            $nicho_escalera_obs = $request->nicho_escalera_obs; 
            $escalones_escalera_obs = $request->escalones_escalera_obs;
            $piso_escalera_obs = $request->piso_escalera_obs;
            $zoclo_escalera_obs = $request->zoclo_escalera_obs;
            $barandal_escalera_obs = $request->barandal_escalera_obs;
            $pasamanos_escalera_obs = $request->pasamanos_escalera_obs;
            $sardinel_escalera_obs = $request->sardinel_escalera_obs;
            $macetero_escalera_obs = $request->macetero_escalera_obs;
            $cajillos_escalera_obs = $request->cajillos_escalera_obs;
            $acc_electricos_escalera_obs = $request->acc_electricos_escalera_obs;
            $acabado_muro_escalera_obs = $request->acabado_muro_escalera_obs;
            $acabado_plafon_escalera_obs = $request->acabado_plafon_escalera_obs;
        ///////////////  BAÑO COMUN  //////////////////
            $puerta_bc = $request->puerta_bc;
            $chapa_bc = $request->chapa_bc;
            $sello_marco_bc = $request->sello_marco_bc;
            $barra_lavabo_bc = $request->barra_lavabo_bc;
            $lavabo_bc = $request->lavabo_bc;
            $monomando_bc = $request->monomando_bc;
            $wc_bc = $request->wc_bc;
            $regadera_bc = $request->regadera_bc;
            $manerales_bc = $request->manerales_bc;
            $coladera_bc = $request->coladera_bc;
            $acc_bano_bc = $request->acc_bano_bc;
            $acc_electricos_bc = $request->acc_electricos_bc;
            $ventana_bc = $request->ventana_bc;
            $sello_ventana_bc = $request->sello_ventana_bc;
            $vidrio_ventana_bc = $request->vidrio_ventana_bc;
            $mosquitero_bc = $request->mosquitero_bc;
            $acabado_muro_bc = $request->acabado_muro_bc;
            $acabado_plafon_bc = $request->acabado_plafon_bc;
            $sardinel_bc = $request->sardinel_bc;
            $piso_bc = $request->piso_bc;
            $zoclo_bc = $request->zoclo_bc;
            ///////////// Observacion ////////////////
            $puerta_bc_obs = $request->puerta_bc_obs;
            $chapa_bc_obs = $request->chapa_bc_obs;
            $sello_marco_bc_obs = $request->sello_marco_bc_obs;
            $barra_lavabo_bc_obs = $request->barra_lavabo_bc_obs;
            $lavabo_bc_obs = $request->lavabo_bc_obs;
            $monomando_bc_obs = $request->monomando_bc_obs;
            $wc_bc_obs = $request->wc_bc_obs;
            $regadera_bc_obs = $request->regadera_bc_obs;
            $manerales_bc_obs = $request->manerales_bc_obs;
            $coladera_bc_obs = $request->coladera_bc_obs;
            $acc_bano_bc_obs = $request->acc_bano_bc_obs;
            $acc_electricos_bc_obs = $request->acc_electricos_bc_obs;
            $ventana_bc_obs = $request->ventana_bc_obs;
            $sello_ventana_bc_obs = $request->sello_ventana_bc_obs;
            $vidrio_ventana_bc_obs = $request->vidrio_ventana_bc_obs;
            $mosquitero_bc_obs = $request->mosquitero_bc_obs;
            $acabado_muro_bc_obs = $request->acabado_muro_bc_obs;
            $acabado_plafon_bc_obs = $request->acabado_plafon_bc_obs;
            $sardinel_bc_obs = $request->sardinel_bc_obs;
            $piso_bc_obs = $request->piso_bc_obs;
            $zoclo_bc_obs = $request->zoclo_bc_obs;
        
        ///////////// ESTANCIA //////////////
            $ventana_estancia = $request->ventana_estancia;
            $sello_ventana_estancia = $request->sello_ventana_estancia;
            $vidrio_ventana_estancia = $request->vidrio_ventana_estancia;
            $mosquitero_estancia = $request->mosquitero_estancia;
            $interfon_estancia = $request->interfon_estancia;
            $acc_electricos_estancia = $request->acc_electricos_estancia;
            $acabado_muro_estancia = $request->acabado_muro_estancia;
            $acabado_plafon_estancia = $request->acabado_plafon_estancia;
            $piso_estancia = $request->piso_estancia;
            $zoclo_estancia = $request->zoclo_estancia;
            //////////// Observacion /////////////
            $ventana_estancia_obs = $request->ventana_estancia_obs;
            $sello_ventana_estancia_obs = $request->sello_ventana_estancia_obs;
            $vidrio_ventana_estancia_obs = $request->vidrio_ventana_estancia_obs;
            $mosquitero_estancia_obs = $request->mosquitero_estancia_obs;
            $interfon_estancia_obs = $request->interfon_estancia_obs;
            $acc_electricos_estancia_obs = $request->acc_electricos_estancia_obs;
            $acabado_muro_estancia_obs = $request->acabado_muro_estancia_obs;
            $acabado_plafon_estancia_obs = $request->acabado_plafon_estancia_obs;
            $piso_estancia_obs = $request->piso_estancia_obs;
            $zoclo_estancia_obs = $request->zoclo_estancia_obs;

        //////////// Recamara Principal //////////////
            $puerta_rp = $request->puerta_rp;
            $chapa_rp = $request->chapa_rp;
            $sello_marco_rp = $request->sello_marco_rp;
            $cancel_rp = $request->cancel_rp;
            $sello_cancel_rp = $request->sello_cancel_rp;
            $vidrio_cancel_rp = $request->vidrio_cancel_rp;
            $mosquitero_rp = $request->mosquitero_rp;
            $balcon_rp = $request->balcon_rp;
            $barandal_rp = $request->barandal_rp;
            $acc_electricos_rp = $request->acc_electricos_rp;
            $interfon_rp = $request->interfon_rp;
            $salida_alarma_rp = $request->salida_alarma_rp;
            $acabado_muro_rp = $request->acabado_muro_rp;
            $acabado_plafon_rp = $request->acabado_plafon_rp;
            $piso_rp = $request->piso_rp;
            $zoclo_rp = $request->zoclo_rp;
            //////////////// Observacion //////////////
            $puerta_rp_obs = $request->puerta_rp_obs;
            $chapa_rp_obs = $request->chapa_rp_obs;
            $sello_marco_rp_obs = $request->sello_marco_rp_obs;
            $cancel_rp_obs = $request->cancel_rp_obs;
            $sello_cancel_rp_obs = $request->sello_cancel_rp_obs;
            $vidrio_cancel_rp_obs = $request->vidrio_cancel_rp_obs;
            $mosquitero_rp_obs = $request->mosquitero_rp_obs;
            $balcon_rp_obs = $request->balcon_rp_obs;
            $barandal_rp_obs = $request->barandal_rp_obs;
            $acc_electricos_rp_obs = $request->acc_electricos_rp_obs;
            $interfon_rp_obs = $request->interfon_rp_obs;
            $salida_alarma_rp_obs = $request->salida_alarma_rp_obs;
            $acabado_muro_rp_obs = $request->acabado_muro_rp_obs;
            $acabado_plafon_rp_obs = $request->acabado_plafon_rp_obs;
            $piso_rp_obs = $request->piso_rp_obs;
            $zoclo_rp_obs = $request->zoclo_rp_obs;

        ///////////// BAÑO RECAMARA PRINCIPAL ////////////////
            $puerta_brp = $request->puerta_brp;
            $chapa_brp = $request->chapa_brp;
            $sello_marco_brp = $request->sello_marco_brp;
            $barra_lavabo_brp = $request->barra_lavabo_brp;
            $lavabo_brp = $request->lavabo_brp;
            $monomando_brp = $request->monomando_brp;
            $wc_brp = $request->wc_brp;
            $regadera_brp = $request->regadera_brp;
            $manerales_brp = $request->manerales_brp;
            $coladera_brp = $request->coladera_brp;
            $acc_bano_brp = $request->acc_bano_brp;
            $acc_electrico_brp = $request->acc_electrico_brp;
            $ventana_brp = $request->ventana_brp;
            $sello_ventana_brp = $request->sello_ventana_brp;
            $vidrio_ventana_brp = $request->vidrio_ventana_brp;
            $mosquitero_brp = $request->mosquitero_brp;
            $acabado_muro_brp = $request->acabado_muro_brp;
            $acabado_plafon_brp = $request->acabado_plafon_brp;
            $sardinel_brp = $request->sardinel_brp;
            $piso_brp = $request->piso_brp;
            $zoclo_brp = $request->zoclo_brp;
            /////////// Observacion ///////////////
            $puerta_brp_obs = $request->puerta_brp_obs;
            $chapa_brp_obs = $request->chapa_brp_obs;
            $sello_marco_brp_obs = $request->sello_marco_brp_obs;
            $barra_lavabo_brp_obs = $request->barra_lavabo_brp_obs;
            $lavabo_brp_obs = $request->lavabo_brp_obs;
            $monomando_brp_obs = $request->monomando_brp_obs;
            $wc_brp_obs = $request->wc_brp_obs;
            $regadera_brp_obs = $request->regadera_brp_obs;
            $manerales_brp_obs = $request->manerales_brp_obs;
            $coladera_brp_obs = $request->coladera_brp_obs;
            $acc_bano_brp_obs = $request->acc_bano_brp_obs;
            $acc_electrico_brp_obs = $request->acc_electrico_brp_obs;
            $ventana_brp_obs = $request->ventana_brp_obs;
            $sello_ventana_brp_obs = $request->sello_ventana_brp_obs;
            $vidrio_ventana_brp_obs = $request->vidrio_ventana_brp_obs;
            $mosquitero_brp_obs = $request->mosquitero_brp_obs;
            $acabado_muro_brp_obs = $request->acabado_muro_brp_obs;
            $acabado_plafon_brp_obs = $request->acabado_plafon_brp_obs;
            $sardinel_brp_obs = $request->sardinel_brp_obs;
            $piso_brp_obs = $request->piso_brp_obs;
            $zoclo_brp_obs = $request->zoclo_brp_obs;

        //////////////// Vestidor //////////////////
            $acc_electrico_vest = $request->acc_electrico_vest;
            $acabado_muro_vest = $request->acabado_muro_vest;
            $acabado_plafon_vest = $request->acabado_plafon_vest;
            $piso_vest = $request->piso_vest;
            $zoclo_vest = $request->zoclo_vest;
            /////////// Observacion ////////////
            $acc_electrico_vest_obs = $request->acc_electrico_vest_obs;
            $acabado_muro_vest_obs = $request->acabado_muro_vest_obs;
            $acabado_plafon_vest_obs = $request->acabado_plafon_vest_obs;
            $piso_vest_obs = $request->piso_vest_obs;
            $zoclo_vest_obs = $request->zoclo_vest_obs;
        /////////////// RECAMARA 2 ///////////////
            $puerta_rec2 = $request->puerta_rec2;
            $chapa_rec2 = $request->chapa_rec2;
            $sello_marco_rec2 = $request->sello_marco_rec2;
            $cancel_rec2 = $request->cancel_rec2;
            $sello_cancel_rec2 = $request->sello_cancel_rec2;
            $vidrio_cancel_rec2 = $request->vidrio_cancel_rec2;
            $mosquitero_rec2 = $request->mosquitero_rec2;
            $acc_rec2 = $request->acc_rec2;
            $salida_alarma_rec2 = $request->salida_alarma_rec2;
            $acabado_muro_rec2 = $request->acabado_muro_rec2;
            $acabado_plafon_rec2 = $request->acabado_plafon_rec2;
            $piso_rec2 = $request->piso_rec2;
            $zoclo_rec2 = $request->zoclo_rec2;
            //////////////  Observacion  ////////////
            $puerta_rec2_obs = $request->puerta_rec2_obs;
            $chapa_rec2_obs = $request->chapa_rec2_obs;
            $sello_marco_rec2_obs = $request->sello_marco_rec2_obs;
            $cancel_rec2_obs = $request->cancel_rec2_obs;
            $sello_cancel_rec2_obs = $request->sello_cancel_rec2_obs;
            $vidrio_cancel_rec2_obs = $request->vidrio_cancel_rec2_obs;
            $mosquitero_rec2_obs = $request->mosquitero_rec2_obs;
            $acc_rec2_obs = $request->acc_rec2_obs;
            $salida_alarma_rec2_obs = $request->salida_alarma_rec2_obs;
            $acabado_muro_rec2_obs = $request->acabado_muro_rec2_obs;
            $acabado_plafon_rec2_obs = $request->acabado_plafon_rec2_obs;
            $piso_rec2_obs = $request->piso_rec2_obs;
            $zoclo_rec2_obs = $request->zoclo_rec2_obs;

        /////////////// RECAMARA 3 ///////////////
            $puerta_rec3 = $request->puerta_rec3;
            $chapa_rec3 = $request->chapa_rec3;
            $sello_marco_rec3 = $request->sello_marco_rec3;
            $cancel_rec3 = $request->cancel_rec3;
            $sello_cancel_rec3 = $request->sello_cancel_rec3;
            $vidrio_cancel_rec3 = $request->vidrio_cancel_rec3;
            $mosquitero_rec3 = $request->mosquitero_rec3;
            $acc_rec3 = $request->acc_rec3;
            $salida_alarma_rec3 = $request->salida_alarma_rec3;
            $acabado_muro_rec3 = $request->acabado_muro_rec3;
            $acabado_plafon_rec3 = $request->acabado_plafon_rec3;
            $piso_rec3 = $request->piso_rec3;
            $zoclo_rec3 = $request->zoclo_rec3;
            //////////////  Observacion  ////////////
            $puerta_rec3_obs = $request->puerta_rec3_obs;
            $chapa_rec3_obs = $request->chapa_rec3_obs;
            $sello_marco_rec3_obs = $request->sello_marco_rec3_obs;
            $cancel_rec3_obs = $request->cancel_rec3_obs;
            $sello_cancel_rec3_obs = $request->sello_cancel_rec3_obs;
            $vidrio_cancel_rec3_obs = $request->vidrio_cancel_rec3_obs;
            $mosquitero_rec3_obs = $request->mosquitero_rec3_obs;
            $acc_rec3_obs = $request->acc_rec3_obs;
            $salida_alarma_rec3_obs = $request->salida_alarma_rec3_obs;
            $acabado_muro_rec3_obs = $request->acabado_muro_rec3_obs;
            $acabado_plafon_rec3_obs = $request->acabado_plafon_rec3_obs;
            $piso_rec3_obs = $request->piso_rec3_obs;
            $zoclo_rec3_obs = $request->zoclo_rec3_obs;
        ///////////// AZOTEA ////////////////
            $pretiles_azotea = $request->pretiles_azotea;
            $impermeabilizacion = $request->impermeabilizacion;
            $domos_azotea = $request->domos_azotea;
            $mufas_azotea = $request->mufas_azotea;
            $jarros_azotea = $request->jarros_azotea;
            $ventilas_azotea = $request->ventilas_azotea;
            $base_tinaco_azotea = $request->base_tinaco_azotea;
            $tinaco_azotea = $request->tinaco_azotea;
            $calentador_solar_azotea = $request->calentador_solar_azotea;
            $punta_gas_azotea = $request->punta_gas_azotea;
            $anclas_azotea = $request->anclas_azotea;
            $limpieza_azotea = $request->limpieza_azotea;
            ///////// Observacion ///////////
            $pretiles_azotea_obs = $request->pretiles_azotea_obs;
            $impermeabilizacion_obs = $request->impermeabilizacion_obs;
            $domos_azotea_obs = $request->domos_azotea_obs;
            $mufas_azotea_obs = $request->mufas_azotea_obs;
            $jarros_azotea_obs = $request->jarros_azotea_obs;
            $ventilas_azotea_obs = $request->ventilas_azotea_obs;
            $base_tinaco_azotea_obs = $request->base_tinaco_azotea_obs;
            $tinaco_azotea_obs = $request->tinaco_azotea_obs;
            $calentador_solar_azotea_obs = $request->calentador_solar_azotea_obs;
            $punta_gas_azotea_obs = $request->punta_gas_azotea_obs;
            $anclas_azotea_obs = $request->anclas_azotea_obs;
            $limpieza_azotea_obs = $request->limpieza_azotea_obs;
        ///////////  GENERALES  //////////////
            $limpieza_interior = $request->limpieza_interior;
            $limpieza_exterior = $request->limpieza_exterior;
            $limpieza_vidrios = $request->limpieza_vidrios;
            $limpieza_domos = $request->limpieza_domos;
            $plastico_muebles = $request->plastico_muebles;
            $candados = $request->candados;
            $llaves = $request->llaves;
            $num_oficial = $request->num_oficial;
            /////////// Observacion ///////////
            $limpieza_interior_obs = $request->limpieza_interior_obs;
            $limpieza_exterior_obs = $request->limpieza_exterior_obs;
            $limpieza_vidrios_obs = $request->limpieza_vidrios_obs;
            $limpieza_domos_obs = $request->limpieza_domos_obs;
            $plastico_muebles_obs = $request->plastico_muebles_obs;
            $candados_obs = $request->candados_obs;
            $llaves_obs = $request->llaves_obs;
            $num_oficial_obs = $request->num_oficial_obs;

         try{
             DB::beginTransaction();
            $revisionPrevia = new Revision_previa();
            $revisionPrevia->id = $folio;
            $revisionPrevia->observaciones = $observacion;
            $revisionPrevia->id_contratista = $id_contratista;
            $revisionPrevia->save();

            $entregas = Entrega::findOrFail($folio);

            if($diferencia <= 1)
                $entregas->revision_previa = 2;
            else{
                $entregas->revision_previa = 1;
            }
            $entregas->save();
                     

            ////////////////// Parte para Registrar detalles /////////
                /////////////////// COCHERA
                    if($mona_cochera == 1){
                        $this->storeDetalle($folio,'Mona',1,$mona_cochera_obs);
                    }
                    if($centro_carga_cochera == 1){
                        $this->storeDetalle($folio,'Centro de carga',1,$centro_carga_cochera_obs);
                    }
                    if($cuadro_hidraulico_cochera == 1){
                        $this->storeDetalle($folio,'Cuadro hidráulico',1,$cuadro_hidraulico_cochera_obs);
                    }
                    if($interfon_cochera == 1){
                        $this->storeDetalle($folio,'Interfon',1,$interfon_cochera_obs);
                    }
                    if($cisterna_cochera == 1){
                        $this->storeDetalle($folio,'Cisterna',1,$cisterna_cochera_obs);
                    }
                    if($bomba_cochera == 1){
                        $this->storeDetalle($folio,'Bomba',1,$bomba_cochera_obs);
                    }
                    if($tapa_cisterna_cochera == 1){
                        $this->storeDetalle($folio,'Tapa Cisterna',1,$tapa_cisterna_cochera_obs);
                    }
                    if($tapa_registro_cochera == 1){
                        $this->storeDetalle($folio,'Tapas registros',1,$tapa_registro_cochera_obs);
                    }
                    if($acc_electrico_cochera == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',1,$acc_electrico_cochera_obs);
                    }
                    if($acabado_muros_cochera == 1){
                        $this->storeDetalle($folio,'Acabado muros',1,$acabado_muros_cochera_obs);
                    }
                    if($acabado_plafon_cochera == 1){
                        $this->storeDetalle($folio,'Acabado plafón',1,$acabado_plafon_cochera_obs);
                    }
                    if($piso_cochera == 1){
                        $this->storeDetalle($folio,'Piso',1,$piso_cochera_obs);
                    }
                    if($zoclo_cochera == 1){
                        $this->storeDetalle($folio,'Zoclo',1,$zoclo_cochera_obs);
                    }

                //////////////////// SALA COMEDOR 
                    if($puerta_pric_sala == 1){
                        $this->storeDetalle($folio,'Puerta principal',2,$puerta_pric_sala_obs);
                    }
                    if($chapa_sala == 1){
                        $this->storeDetalle($folio,'Chapa',2,$chapa_sala_obs);
                    }
                    if($sello_marco_sala == 1){
                        $this->storeDetalle($folio,'Sello marco',2,$sello_marco_sala_obs);
                    }
                    if($ventana_sala == 1){
                        $this->storeDetalle($folio,'Ventana',2,$ventana_sala_obs);
                    }
                    if($sello_ventana_sala == 1){
                        $this->storeDetalle($folio,'Sello ventana',2,$sello_ventana_sala_obs);
                    }
                    if($vidrio_ventana_sala == 1){
                        $this->storeDetalle($folio,'Vidrio ventana',2,$vidrio_ventana_sala_obs);
                    }
                    if($mosquitero_sala == 1){
                        $this->storeDetalle($folio,'Mosquitero',2,$mosquitero_sala_obs);
                    }
                    if($cancel_sala == 1){
                        $this->storeDetalle($folio,'Cancel',2,$cancel_sala_obs);
                    }
                    if($sello_cancel_sala == 1){
                        $this->storeDetalle($folio,'Sello cancel',2,$sello_cancel_sala_obs);
                    }
                    if($vidrio_cancel_sala == 1){
                        $this->storeDetalle($folio,'Vidrio cancel',2,$vidrio_cancel_sala_obs);
                    }
                    if($salida_alarma_sala == 1){
                        $this->storeDetalle($folio,'Salida alarma',2,$salida_alarma_sala_obs);
                    }
                    if($acc_electrico_sala == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',2,$acc_electrico_sala_obs);
                    }
                    if($acabado_muros_sala == 1){
                        $this->storeDetalle($folio,'Acabado muros',2,$acabado_muros_sala_obs);
                    }
                    if($acabado_plafon_sala == 1){
                        $this->storeDetalle($folio,'Acabado plafón',2,$acabado_plafon_sala_obs);
                    }
                    if($piso_sala == 1){
                        $this->storeDetalle($folio,'Piso',2,$piso_sala_obs);
                    }
                    if($zoclo_sala == 1){
                        $this->storeDetalle($folio,'Zoclo',2,$zoclo_sala_obs);
                    }
                /////////////// COCINA
                    if($tarja_cocina == 1){
                        $this->storeDetalle($folio,'Tarja',3,$tarja_cocina_obs);
                    }
                    if($puerta_cocina == 1){
                        $this->storeDetalle($folio,'Puerta/ventana',3,$puerta_cocina_obs);
                    }
                    if($chapa_cocina == 1){
                        $this->storeDetalle($folio,'Chapa',3,$chapa_cocina_obs);
                    }
                    if($sello_pv_cocina == 1){
                        $this->storeDetalle($folio,'Sello en p/v',3,$sello_pv_cocina_obs);
                    }
                    if($vidrio_pv_cocina == 1){
                        $this->storeDetalle($folio,'Vidrio en p/v',3,$vidrio_pv_cocina_obs);
                    }
                    if($mosquitero_cocina == 1){
                        $this->storeDetalle($folio,'Mosquitero',3,$mosquitero_cocina_obs);
                    }
                    if($salida_alarma_cocina == 1){
                        $this->storeDetalle($folio,'Salida de alarma',3,$salida_alarma_cocina_obs);
                    }
                    if($interfon_cocina == 1){
                        $this->storeDetalle($folio,'Interfón',3,$interfon_cocina_obs);
                    }
                    if($acc_electrico_cocina == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',3,$acc_electrico_cocina_obs);
                    }
                    if($centro_carga_cocina == 1){
                        $this->storeDetalle($folio,'Centro de carga',3,$centro_carga_cocina_obs);
                    }
                    if($inst_gas_cocina == 1){
                        $this->storeDetalle($folio,'Inst. Gas',3,$inst_gas_cocina_obs);
                    }
                    if($inst_refrigerador_cocina == 1){
                        $this->storeDetalle($folio,'Inst. Refrigerador',3,$inst_refrigerador_cocina_obs);
                    }
                    if($barra_cocina == 1){
                        $this->storeDetalle($folio,'Barra',3,$barra_cocina_obs);
                    }
                    if($azulejo_cocina == 1){
                        $this->storeDetalle($folio,'Azulejo muro',3,$azulejo_cocina_obs);
                    }
                    if($acabado_muro_cocina == 1){
                        $this->storeDetalle($folio,'Acabado muros',3,$acabado_muro_cocina_obs);
                    }
                    if($acabado_plafon_cocina == 1){
                        $this->storeDetalle($folio,'Acabado plafón',3,$acabado_plafon_cocina_obs);
                    }
                    if($piso_cocina == 1){
                        $this->storeDetalle($folio,'Piso',3,$piso_cocina_obs);
                    }
                    if($zoclo_cocina == 1){
                        $this->storeDetalle($folio,'Zoclo',3,$zoclo_cocina_obs);
                    }

                //////////////////// MEDIO BAÑO
                    if($puerta_mb == 1){
                        $this->storeDetalle($folio,'Puerta',4,$puerta_mb_obs);
                    }
                    if($chapa_mb == 1){
                        $this->storeDetalle($folio,'Chapa',4,$chapa_mb_obs);
                    }
                    if($barra_lavabo_mb == 1){
                        $this->storeDetalle($folio,'Barra lavabo',4,$barra_lavabo_mb_obs);
                    }
                    if($lavabo_mb == 1){
                        $this->storeDetalle($folio,'Lavabo',4,$lavabo_mb_obs);
                    }
                    if($monomando_mb == 1){
                        $this->storeDetalle($folio,'Monomando',4,$monomando_mb_obs);
                    }
                    if($wc_mb == 1){
                        $this->storeDetalle($folio,'WC',4,$wc_mb_obs);
                    }
                    if($acc_bano_mb == 1){
                        $this->storeDetalle($folio,'Acc. Baño',4,$acc_bano_mb_obs);
                    }
                    if($acc_electrico_mb == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',4,$acc_electrico_mb_obs);
                    }
                    if($ventana_mb == 1){
                        $this->storeDetalle($folio,'Ventana',4,$ventana_mb_obs);
                    }
                    if($sello_ventana_mb == 1){
                        $this->storeDetalle($folio,'Sello ventana',4,$sello_ventana_mb_obs);
                    }
                    if($vidrio_mb == 1){
                        $this->storeDetalle($folio,'Vidrio ventana',4,$vidrio_mb_obs);
                    }
                    if($mosquitero_mb == 1){
                        $this->storeDetalle($folio,'Mosquitero',4,$mosquitero_mb_obs);
                    }
                    if($acabado_muro_mb == 1){
                        $this->storeDetalle($folio,'Acabado muros',4,$acabado_muro_mb_obs);
                    }
                    if($acabado_plafon_mb == 1){
                        $this->storeDetalle($folio,'Acabado plafón',4,$acabado_plafon_mb_obs);
                    }
                    if($piso_mb == 1){
                        $this->storeDetalle($folio,'Piso',4,$piso_mb_obs);
                    }
                    if($zoclo_mb == 1){
                        $this->storeDetalle($folio,'Puerta',4,$zoclo_mb_obs);
                    }

                /////////////// PATIO
                    if($calentador_patio == 1){
                        $this->storeDetalle($folio,'Calentador',5,$calentador_patio_obs);
                    }
                    if($inst_gas_patio == 1){
                        $this->storeDetalle($folio,'Inst. Gas',5,$inst_gas_patio_obs);
                    }
                    if($acc_electrico_patio == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',5,$acc_electrico_patio_obs);
                    }
                    if($lavadero_patio == 1){
                        $this->storeDetalle($folio,'Lavadero',5,$lavadero_patio_obs);
                    }
                    if($llaves_nariz_patio == 1){
                        $this->storeDetalle($folio,'Llaves nariz',5,$llaves_nariz_patio_obs);
                    }
                    if($descarga_lavadora_patio == 1){
                        $this->storeDetalle($folio,'Descarga lavadora',5,$descarga_lavadora_patio_obs);
                    }
                    if($coladera_patio == 1){
                        $this->storeDetalle($folio,'Coladera',5,$coladera_patio_obs);
                    }
                    if($tapa_registro_patio == 1){
                        $this->storeDetalle($folio,'Tapa registro',5,$tapa_registro_patio_obs);
                    }
                    if($escalera_marina_patio == 1){
                        $this->storeDetalle($folio,'Escalera marina',5,$escalera_marina_patio_obs);
                    }
                    if($techumbre_patio == 1){
                        $this->storeDetalle($folio,'Techumbre patio',5,$techumbre_patio_obs);
                    }
                    if($firme_cilindros_patio == 1){
                        $this->storeDetalle($folio,'Firme cilindros',5,$firme_cilindros_patio_obs);
                    }
                    if($rodapie_patio == 1){
                        $this->storeDetalle($folio,'Rodapie',5,$rodapie_patio_obs);
                    }
                    if($acabado_muros_patio == 1){
                        $this->storeDetalle($folio,'Acabado muros',5,$acabado_muros_patio_obs);
                    }
                    if($acabado_volado_patio == 1){
                        $this->storeDetalle($folio,'Acabado volado',5,$acabado_volado_patio_obs);
                    }
                    if($piso_patio == 1){
                        $this->storeDetalle($folio,'Piso',5,$piso_patio_obs);
                    }
                    if($zoclo_patio == 1){
                        $this->storeDetalle($folio,'Zoclo',5,$zoclo_patio_obs);
                    }

                ////////////// ESCALERA
                    if($nicho_escalera == 1){
                        $this->storeDetalle($folio,'Nicho',6,$nicho_escalera_obs);
                    }
                    if($escalones_escalera == 1){
                        $this->storeDetalle($folio,'Escalones',6,$escalones_escalera_obs);
                    }
                    if($piso_escalera == 1){
                        $this->storeDetalle($folio,'Piso',6,$piso_escalera_obs);
                    }
                    if($zoclo_escalera == 1){
                        $this->storeDetalle($folio,'Zoclo',6,$zoclo_escalera_obs);
                    }
                    if($barandal_escalera == 1){
                        $this->storeDetalle($folio,'Barandal',6,$barandal_escalera_obs);
                    }
                    if($pasamanos_escalera == 1){
                        $this->storeDetalle($folio,'Pasamanos',6,$pasamanos_escalera_obs);
                    }
                    if($sardinel_escalera == 1){
                        $this->storeDetalle($folio,'Sardinel',6,$sardinel_escalera_obs);
                    }
                    if($macetero_escalera == 1){
                        $this->storeDetalle($folio,'Macetero',6,$macetero_escalera_obs);
                    }
                    if($cajillos_escalera == 1){
                        $this->storeDetalle($folio,'Cajillos',6,$cajillos_escalera_obs);
                    }
                    if($acc_electricos_escalera == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',6,$acc_electricos_escalera_obs);
                    }
                    if($acabado_muros_patio == 1){
                        $this->storeDetalle($folio,'Acabado muros',6,$acabado_muros_patio_obs);
                    }
                    if($acabado_plafon_escalera == 1){
                        $this->storeDetalle($folio,'Acabado plafón',6,$acabado_plafon_escalera_obs);
                    }

                ///////////// BAÑO COMÚN
                    if($puerta_bc == 1){
                        $this->storeDetalle($folio,'Puerta',7,$puerta_bc_obs);
                    }
                    if($chapa_bc == 1){
                        $this->storeDetalle($folio,'Chapa',7,$chapa_bc_obs);
                    }
                    if($sello_marco_bc == 1){
                        $this->storeDetalle($folio,'Sello marco',7,$sello_marco_bc_obs);
                    }
                    if($barra_lavabo_bc == 1){
                        $this->storeDetalle($folio,'Barra lavabo',7,$barra_lavabo_bc_obs);
                    }
                    if($lavabo_bc == 1){
                        $this->storeDetalle($folio,'Lavabo',7,$lavabo_bc_obs);
                    }
                    if($monomando_bc == 1){
                        $this->storeDetalle($folio,'Monomando',7,$monomando_bc_obs);
                    }
                    if($wc_bc == 1){
                        $this->storeDetalle($folio,'WC',7,$wc_bc_obs);
                    }
                    if($regadera_bc == 1){
                        $this->storeDetalle($folio,'Regadera',7,$regadera_bc_obs);
                    }
                    if($manerales_bc == 1){
                        $this->storeDetalle($folio,'Manerales',7,$manerales_bc_obs);
                    }
                    if($coladera_bc == 1){
                        $this->storeDetalle($folio,'Coladera',7,$coladera_bc_obs);
                    }
                    if($acc_bano_bc == 1){
                        $this->storeDetalle($folio,'Acc. Baño',7,$acc_bano_bc_obs);
                    }
                    if($acc_electricos_bc == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',7,$acc_electricos_bc_obs);
                    }
                    if($ventana_bc == 1){
                        $this->storeDetalle($folio,'Ventana',7,$ventana_bc_obs);
                    }
                    if($sello_ventana_bc == 1){
                        $this->storeDetalle($folio,'Sello ventana',7,$sello_ventana_bc_obs);
                    }
                    if($vidrio_ventana_bc == 1){
                        $this->storeDetalle($folio,'Vidrio ventana',7,$vidrio_ventana_bc_obs);
                    }
                    if($mosquitero_bc == 1){
                        $this->storeDetalle($folio,'Mosquitero',7,$mosquitero_bc_obs);
                    }
                    if($acabado_muro_bc == 1){
                        $this->storeDetalle($folio,'Acabado muros',7,$acabado_muro_bc_obs);
                    }
                    if($acabado_plafon_bc == 1){
                        $this->storeDetalle($folio,'Acabado plafón',7,$acabado_plafon_bc_obs);
                    }
                    if($sardinel_bc == 1){
                        $this->storeDetalle($folio,'Sardinel',7,$sardinel_bc_obs);
                    }
                    if($piso_bc == 1){
                        $this->storeDetalle($folio,'Piso',7,$piso_bc_obs);
                    }
                    if($zoclo_bc == 1){
                        $this->storeDetalle($folio,'Zoclo',7,$zoclo_bc_obs);
                    }

                ///////////////// ESTANCIA
                    if($ventana_estancia == 1){
                        $this->storeDetalle($folio,'Ventana',8,$ventana_estancia_obs);
                    }
                    if($sello_ventana_estancia == 1){
                        $this->storeDetalle($folio,'Sello ventana',8,$sello_ventana_estancia_obs);
                    }
                    if($vidrio_ventana_estancia == 1){
                        $this->storeDetalle($folio,'Vidrio ventana',8,$vidrio_ventana_estancia_obs);
                    }
                    if($mosquitero_estancia == 1){
                        $this->storeDetalle($folio,'Mosquitero',8,$mosquitero_estancia_obs);
                    }
                    if($interfon_estancia == 1){
                        $this->storeDetalle($folio,'Interfón',8,$interfon_estancia_obs);
                    }
                    if($acc_electricos_estancia == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',8,$acc_electricos_estancia_obs);
                    }
                    if($acabado_muro_estancia == 1){
                        $this->storeDetalle($folio,'Acabado muros',8,$acabado_muro_estancia_obs);
                    }
                    if($acabado_plafon_estancia == 1){
                        $this->storeDetalle($folio,'Acabado plafon',8,$acabado_plafon_estancia_obs);
                    }
                    if($piso_estancia == 1){
                        $this->storeDetalle($folio,'Piso',8,$piso_estancia_obs);
                    }
                    if($zoclo_estancia == 1){
                        $this->storeDetalle($folio,'Zoclo',8,$zoclo_estancia_obs);
                    }
                
                ////////// RECAMARA PRINCIPAL
                    if($puerta_rp == 1){
                        $this->storeDetalle($folio,'Puerta',9,$puerta_rp_obs);
                    }
                    if($chapa_rp == 1){
                        $this->storeDetalle($folio,'Chapa',9,$chapa_rp_obs);
                    }
                    if($sello_marco_rp == 1){
                        $this->storeDetalle($folio,'Sello marco',9,$sello_marco_rp_obs);
                    }
                    if($cancel_rp == 1){
                        $this->storeDetalle($folio,'Cancel',9,$cancel_rp_obs);
                    }
                    if($sello_cancel_rp == 1){
                        $this->storeDetalle($folio,'Sello cancel',9,$sello_cancel_rp_obs);
                    }
                    if($vidrio_cancel_rp == 1){
                        $this->storeDetalle($folio,'Vidrio cancel',9,$vidrio_cancel_rp_obs);
                    }
                    if($mosquitero_rp == 1){
                        $this->storeDetalle($folio,'Mosquitero',9,$mosquitero_rp_obs);
                    }
                    if($balcon_rp == 1){
                        $this->storeDetalle($folio,'Balcón',9,$balcon_rp_obs);
                    }
                    if($barandal_rp == 1){
                        $this->storeDetalle($folio,'Barandal',9,$barandal_rp_obs);
                    }
                    if($acc_electricos_rp == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',9,$acc_electricos_rp_obs);
                    }
                    if($interfon_rp == 1){
                        $this->storeDetalle($folio,'Interfón',9,$interfon_rp_obs);
                    }
                    if($salida_alarma_rp == 1){
                        $this->storeDetalle($folio,'Salida alarma',9,$salida_alarma_rp_obs);
                    }
                    if($acabado_muro_rp == 1){
                        $this->storeDetalle($folio,'Acabado muros',9,$acabado_muro_rp_obs);
                    }
                    if($acabado_plafon_rp == 1){
                        $this->storeDetalle($folio,'Acabado plafón',9,$acabado_plafon_rp_obs);
                    }
                    if($piso_rp == 1){
                        $this->storeDetalle($folio,'Piso',9,$piso_rp_obs);
                    }
                    if($zoclo_rp == 1){
                        $this->storeDetalle($folio,'Zoclo',9,$zoclo_rp_obs);
                    }
                
                /////////// BAÑO RECAMARA PRINCIPAL
                    if($puerta_brp == 1){
                        $this->storeDetalle($folio,'Puerta',10,$puerta_brp_obs);
                    }
                    if($chapa_brp == 1){
                        $this->storeDetalle($folio,'Chapa',10,$chapa_brp_obs);
                    }
                    if($sello_marco_brp == 1){
                        $this->storeDetalle($folio,'Sello marco',10,$sello_marco_brp_obs);
                    }
                    if($barra_lavabo_brp == 1){
                        $this->storeDetalle($folio,'Barra lavabo',10,$barra_lavabo_brp_obs);
                    }
                    if($lavabo_brp == 1){
                        $this->storeDetalle($folio,'Lavabo',10,$lavabo_brp_obs);
                    }
                    if($monomando_brp == 1){
                        $this->storeDetalle($folio,'Monomando',10,$monomando_brp_obs);
                    }
                    if($wc_brp == 1){
                        $this->storeDetalle($folio,'WC',10,$wc_brp_obs);
                    }
                    if($regadera_brp == 1){
                        $this->storeDetalle($folio,'Regadera',10,$regadera_brp_obs);
                    }
                    if($manerales_brp == 1){
                        $this->storeDetalle($folio,'Manerales',10,$manerales_brp_obs);
                    }
                    if($coladera_brp == 1){
                        $this->storeDetalle($folio,'Coladera',10,$coladera_brp_obs);
                    }
                    if($acc_bano_brp == 1){
                        $this->storeDetalle($folio,'Acc. Baño',10,$acc_bano_brp_obs);
                    }
                    if($acc_electrico_brp == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',10,$acc_electrico_brp_obs);
                    }
                    if($ventana_brp == 1){
                        $this->storeDetalle($folio,'Ventana',10,$ventana_brp_obs);
                    }
                    if($sello_ventana_brp == 1){
                        $this->storeDetalle($folio,'Sello ventana',10,$sello_ventana_brp_obs);
                    }
                    if($vidrio_ventana_brp == 1){
                        $this->storeDetalle($folio,'Vidrio ventana',10,$vidrio_ventana_brp_obs);
                    }
                    if($mosquitero_brp == 1){
                        $this->storeDetalle($folio,'Mosquitero',10,$mosquitero_brp_obs);
                    }
                    if($acabado_muro_brp == 1){
                        $this->storeDetalle($folio,'Acabado muros',10,$acabado_muro_brp_obs);
                    }
                    if($acabado_plafon_brp == 1){
                        $this->storeDetalle($folio,'Acabado plafón',10,$acabado_plafon_brp_obs);
                    }
                    if($sardinel_brp == 1){
                        $this->storeDetalle($folio,'Sardinel',10,$sardinel_brp_obs);
                    }
                    if($piso_brp == 1){
                        $this->storeDetalle($folio,'Piso',10,$piso_brp_obs);
                    }
                    if($zoclo_brp == 1){
                        $this->storeDetalle($folio,'Zoclo',10,$zoclo_brp_obs);
                    }

                ////////////////// VESTIDOR
                    if($acc_electrico_vest == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',11,$acc_electrico_vest_obs);
                    }
                    if($acabado_muro_vest == 1){
                        $this->storeDetalle($folio,'Acabado muros',11,$acabado_muro_vest_obs);
                    }
                    if($acabado_plafon_vest == 1){
                        $this->storeDetalle($folio,'Acabado plafón',11,$acabado_plafon_vest_obs);
                    }
                    if($piso_vest == 1){
                        $this->storeDetalle($folio,'Piso',11,$piso_vest_obs);
                    }
                    if($zoclo_vest == 1){
                        $this->storeDetalle($folio,'Zoclo',11,$zoclo_vest_obs);
                    }
                    if($zoclo_vest == 1){
                        $this->storeDetalle($folio,'Zoclo',11,$zoclo_vest_obs);
                    }

                ////////////////// RECAMARA 2
                    if($puerta_rec2 == 1){
                        $this->storeDetalle($folio,'Puerta',12,$puerta_rec2_obs);
                    }
                    if($chapa_rec2 == 1){
                        $this->storeDetalle($folio,'Chapa',12,$chapa_rec2_obs);
                    }
                    if($sello_marco_rec2 == 1){
                        $this->storeDetalle($folio,'Sello marco',12,$sello_marco_rec2_obs);
                    }
                    if($cancel_rec2 == 1){
                        $this->storeDetalle($folio,'Cancel',12,$cancel_rec2_obs);
                    }
                    if($sello_cancel_rec2 == 1){
                        $this->storeDetalle($folio,'Sello cancel',12,$sello_cancel_rec2_obs);
                    }
                    if($vidrio_cancel_rec2 == 1){
                        $this->storeDetalle($folio,'Vidrio cancel',12,$vidrio_cancel_rec2_obs);
                    }
                    if($mosquitero_rec2 == 1){
                        $this->storeDetalle($folio,'Mosquitero',12,$mosquitero_rec2_obs);
                    }
                    if($acc_rec2 == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',12,$acc_rec2_obs);
                    }
                    if($salida_alarma_rec2 == 1){
                        $this->storeDetalle($folio,'Salida alarma',12,$salida_alarma_rec2_obs);
                    }
                    if($acabado_muro_rec2 == 1){
                        $this->storeDetalle($folio,'Acabado muros',12,$acabado_muro_rec2_obs);
                    }
                    if($acabado_plafon_rec2 == 1){
                        $this->storeDetalle($folio,'Acabado plafón',12,$acabado_plafon_rec2_obs);
                    }
                    if($piso_rec2 == 1){
                        $this->storeDetalle($folio,'Piso',12,$piso_rec2_obs);
                    }
                    if($zoclo_rec2 == 1){
                        $this->storeDetalle($folio,'Zoclo',12,$zoclo_rec2_obs);
                    }
                ////////////////// RECAMARA 3
                    if($puerta_rec3 == 1){
                        $this->storeDetalle($folio,'Puerta',13,$puerta_rec3_obs);
                    }
                    if($chapa_rec3 == 1){
                        $this->storeDetalle($folio,'Chapa',13,$chapa_rec3_obs);
                    }
                    if($sello_marco_rec3 == 1){
                        $this->storeDetalle($folio,'Sello marco',13,$sello_marco_rec3_obs);
                    }
                    if($cancel_rec3 == 1){
                        $this->storeDetalle($folio,'Cancel',13,$cancel_rec3_obs);
                    }
                    if($sello_cancel_rec3 == 1){
                        $this->storeDetalle($folio,'Sello cancel',13,$sello_cancel_rec3_obs);
                    }
                    if($vidrio_cancel_rec3 == 1){
                        $this->storeDetalle($folio,'Vidrio cancel',13,$vidrio_cancel_rec3_obs);
                    }
                    if($mosquitero_rec3 == 1){
                        $this->storeDetalle($folio,'Mosquitero',13,$mosquitero_rec3_obs);
                    }
                    if($acc_rec3 == 1){
                        $this->storeDetalle($folio,'Acc. Eléctricos',13,$acc_rec3_obs);
                    }
                    if($salida_alarma_rec3 == 1){
                        $this->storeDetalle($folio,'Salida alarma',13,$salida_alarma_rec3_obs);
                    }
                    if($acabado_muro_rec3 == 1){
                        $this->storeDetalle($folio,'Acabado muros',13,$acabado_muro_rec3_obs);
                    }
                    if($acabado_plafon_rec3 == 1){
                        $this->storeDetalle($folio,'Acabado plafón',13,$acabado_plafon_rec3_obs);
                    }
                    if($piso_rec3 == 1){
                        $this->storeDetalle($folio,'Piso',13,$piso_rec3_obs);
                    }
                    if($zoclo_rec3 == 1){
                        $this->storeDetalle($folio,'Zoclo',13,$zoclo_rec3_obs);
                    }
                
                //////////////// AZOTEA
                    if($pretiles_azotea == 1){
                        $this->storeDetalle($folio,'Pretiles',14,$pretiles_azotea_obs);
                    }
                    if($impermeabilizacion == 1){
                        $this->storeDetalle($folio,'Impermeabilización',14,$impermeabilizacion_obs);
                    }
                    if($domos_azotea == 1){
                        $this->storeDetalle($folio,'Domos',14,$domos_azotea_obs);
                    }
                    if($mufas_azotea == 1){
                        $this->storeDetalle($folio,'Mufas',14,$mufas_azotea_obs);
                    }
                    if($jarros_azotea == 1){
                        $this->storeDetalle($folio,'Jarros de aire',14,$jarros_azotea_obs);
                    }
                    if($ventilas_azotea == 1){
                        $this->storeDetalle($folio,'Ventilas inst. Sn.',14,$ventilas_azotea_obs);
                    }
                    if($base_tinaco_azotea == 1){
                        $this->storeDetalle($folio,'Base tinaco',14,$base_tinaco_azotea_obs);
                    }
                    if($tinaco_azotea == 1){
                        $this->storeDetalle($folio,'Tinaco',14,$tinaco_azotea_obs);
                    }
                    if($calentador_solar_azotea == 1){
                        $this->storeDetalle($folio,'Calentador solar',14,$calentador_solar_azotea_obs);
                    }
                    if($punta_gas_azotea == 1){
                        $this->storeDetalle($folio,'Punta inst. Gas',14,$punta_gas_azotea_obs);
                    }
                    if($anclas_azotea == 1){
                        $this->storeDetalle($folio,'Anclas escalera',14,$anclas_azotea_obs);
                    }
                    if($limpieza_azotea == 1){
                        $this->storeDetalle($folio,'Limpieza',14,$limpieza_azotea_obs);
                    }

                ///////////// GENERALES
                    if($limpieza_interior == 1){
                        $this->storeDetalle($folio,'Limpieza interior',15,$limpieza_interior_obs);
                    }
                    if($limpieza_exterior == 1){
                        $this->storeDetalle($folio,'Limpieza exterior',15,$limpieza_exterior_obs);
                    }
                    if($limpieza_vidrios == 1){
                        $this->storeDetalle($folio,'Limpieza en vidrios',15,$limpieza_vidrios_obs);
                    }
                    if($limpieza_domos == 1){
                        $this->storeDetalle($folio,'Limpieza en domos',15,$limpieza_domos_obs);
                    }
                    if($plastico_muebles == 1){
                        $this->storeDetalle($folio,'Plástico en muebles',15,$plastico_muebles_obs);
                    }
                    if($candados == 1){
                        $this->storeDetalle($folio,'Candados',15,$candados_obs);
                    }
                    if($llaves == 1){
                        $this->storeDetalle($folio,'Llaves',15,$llaves_obs);
                    }
                    if($num_oficial == 1){
                        $this->storeDetalle($folio,'Número oficial',15,$num_oficial_obs);
                    }

                DB::commit();

            } catch (Exception $e){
                DB::rollBack();
            } 
                    

    }

    public function storeDetalle($folio,$concepto,$id,$obs){
        $detalle = new Detalle_previo();
        $detalle->rev_previas_id = $folio;
        $detalle->identificador = $id;
        $detalle->concepto = $concepto;
        $detalle->observacion = $obs;
        $detalle->save();
    }


    public function DetallesPDF($folio){
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
                        'creditos.modelo',
                        
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
                    ->where('entregas.fecha_program','!=',NULL)
                    ->where('contratos.id','=',$folio)
                    ->orderBy('entregas.revision_previa','asc')
                    ->orderBy('entregas.fecha_program','asc')
                    ->get();

                    $detalles = Detalle_previo::select('concepto','observacion','identificador')
                    ->where('rev_previas_id','=',$folio)
                    ->get();

                    $observacion = Revision_previa::select('observaciones','created_at')->where('id','=',$folio)->get();
                    $tiempo = new Carbon($observacion[0]->created_at);
                    $observacion[0]->tiempo = $tiempo->formatLocalized('%d de %b de %Y');

                    $equipamientos = Solic_equipamiento::join('equipamientos','solic_equipamientos.equipamiento_id','=','equipamientos.id')
                                    ->select('equipamientos.equipamiento')
                                    ->where('solic_equipamientos.contrato_id','=',$folio)
                                    ->get();

                    
                    for ($i = 0; $i < count($detalles); $i++) {
                       switch($detalles[$i]->identificador){
                           case 1 : {
                            $contratos[0]->cochera = 1;
                            break;
                           }
                           case 2 : {
                            $contratos[0]->sala_comedor = 2;
                            break;
                           }
                           case 3 : {
                            $contratos[0]->cocina = 3;
                            break;
                           }
                           case 4 : {
                            $contratos[0]->medio_baño = 4;
                            break;
                           }
                           case 5 : {
                            $contratos[0]->patio = 5;
                            break;
                           }
                           case 6 : {
                            $contratos[0]->escalera = 6;
                            break;
                           }
                           case 7 : {
                            $contratos[0]->baño_comun = 7;
                            break;
                           }
                           case 8 : {
                            $contratos[0]->estancia = 8;
                            break;
                           }
                           case 9 : {
                            $contratos[0]->recamara_principal = 9;
                            break;
                           }
                           case 10 : {
                            $contratos[0]->baño_recamara_principal = 10;
                            break;
                           }
                           case 11 : {
                            $contratos[0]->vestidor = 11;
                            break;
                           }
                           case 12 : {
                            $contratos[0]->recamara2 = 12;
                            break;
                           }
                           case 13 : {
                            $contratos[0]->recamara3 = 13;
                            break;
                           }
                           case 14 : {
                            $contratos[0]->azotea = 14;
                            break;
                           }
                           case 15 : {
                            $contratos[0]->generales = 15;
                            break;
                           }
                           
                       }     
                    }
    
                    $pdf = \PDF::loadview('pdf.DocsPostVenta.checklist', ['contratos' => $contratos, 'detalles' => $detalles, 'observacion' => $observacion, 'equipamientos' => $equipamientos]);
                    return $pdf->stream('Checklist.pdf');

    }
    
}
