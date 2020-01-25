<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avaluo;
use App\Contrato;
use App\Avaluo_status;
use Auth;
use App\Gasto_admin;
use Carbon\Carbon;
use App\User;
use DB;
use App\Notifications\NotifyAdmin;
use App\Obs_expediente;
use File;
use Excel;


class AvaluoController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avaluo = new Avaluo();
        $avaluo->contrato_id = $request->folio;
        $avaluo->fecha_solicitud = $request->fecha_solicitud;
        $avaluo->valor_requerido = $request->valor_requerido;
        $avaluo->save();

        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = $request->fecha_solicitud;
        $contrato->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "Nueva solicitud de avaluo para el contrato #" . $request->folio;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Avaluo'
                    ]
                ];


                User::findOrFail(3)->notify(new NotifyAdmin($arregloAceptado));
                //User::findOrFail(23680)->notify(new NotifyAdmin($arregloAceptado));


    }

    public function noAplicaAvaluo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contrato = Contrato::findOrFail($request->folio);
        $contrato->avaluo_preventivo = "0000-01-01";
        $contrato->save();
    }

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        if($buscar == ''){
            $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('avaluos.fecha_recibido','=',NULL)
                    ->where('contratos.status','!=',2)
                    ->where('contratos.status','!=',0)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->paginate(12);

        }
        else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->where('avaluos.fecha_recibido','=',NULL)
                        ->where('contratos.status','!=',2)
                        ->where('contratos.status','!=',0)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(12);

                    }
                    break;
                }
                case 'licencias.visita_avaluo':{
                    $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('licencias.visita_avaluo','=',$buscar)
                    ->where('avaluos.fecha_recibido','=',NULL)
                    ->where('contratos.status','!=',2)
                    ->where('contratos.status','!=',0)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->paginate(12);
                    break;
                }
            }
        }
        
        return [
            'pagination' => [
            'total'         => $avaluos->total(),
            'current_page'  => $avaluos->currentPage(),
            'per_page'      => $avaluos->perPage(),
            'last_page'     => $avaluos->lastPage(),
            'from'          => $avaluos->firstItem(),
            'to'            => $avaluos->lastItem(),
        ],'avaluos' => $avaluos];
    }

    public function indexHistorial(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        if($buscar == ''){
            $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('avaluos.fecha_recibido','!=',NULL)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->paginate(25);

        }
        else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('avaluos.fecha_recibido','!=',NULL)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->paginate(25);

                    }
                    break;
                }
                case 'licencias.visita_avaluo':{
                    $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('avaluos.fecha_recibido','!=',NULL)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->paginate(25);
                    break;
                }
            }
        }
        
        return [
            'pagination' => [
            'total'         => $avaluos->total(),
            'current_page'  => $avaluos->currentPage(),
            'per_page'      => $avaluos->perPage(),
            'last_page'     => $avaluos->lastPage(),
            'from'          => $avaluos->firstItem(),
            'to'            => $avaluos->lastItem(),
        ],'avaluos' => $avaluos];
    }

    public function excelHistorial(Request $request){
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;

        if($buscar == ''){
            $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('avaluos.fecha_recibido','!=',NULL)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->get();

        }
        else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('avaluos.fecha_recibido','!=',NULL)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote == ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa != '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa != '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.etapa_id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa == '' && $b_manzana =='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    elseif($b_etapa == '' && $b_manzana !='' && $b_lote != ''){
                        $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('clientes','creditos.prospecto_id','=','clientes.id')
                        ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                        ->join('personal','clientes.id','=','personal.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select(
                            'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                            'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                            'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                            'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                            'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                            'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                        )
                        ->where('inst_seleccionadas.elegido','=','1')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('avaluos.fecha_recibido','asc')
                        ->get();

                    }
                    break;
                }
                case 'licencias.visita_avaluo':{
                    $avaluos = Avaluo::join('contratos','avaluos.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('clientes','creditos.prospecto_id','=','clientes.id')
                    ->join('inst_seleccionadas','creditos.id','=','inst_seleccionadas.credito_id')
                    ->join('personal','clientes.id','=','personal.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->select(
                        'contratos.id as folio','lotes.num_lote','personal.nombre','personal.apellidos',
                        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.modelo',
                        'licencias.avance','avaluos.fecha_solicitud','avaluos.valor_requerido','avaluos.observacion',
                        'avaluos.id as avaluoId','avaluos.fecha_recibido','avaluos.resultado','licencias.visita_avaluo',
                        'avaluos.fecha_ava_sol','avaluos.fecha_pago','avaluos.status','avaluos.costo','avaluos.fecha_concluido',
                        'inst_seleccionadas.tipo_credito', 'avaluos.pdf'

                    )
                    ->where('inst_seleccionadas.elegido','=','1')
                    ->where('avaluos.fecha_recibido','!=',NULL)
                    ->orderBy('avaluos.fecha_recibido','asc')
                    ->get();
                    break;
                }
            }
        }
        
        return Excel::create('Avaluos', function($excel) use ($avaluos){
                $excel->sheet('Avaluos', function($sheet) use ($avaluos){
                    
                    $sheet->row(1, [
                        '# Contrato', 'Cliente','Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo', 
                        'Avance de obra', 'Fecha de Solicitud (Ventas)', 'Valor Solicitado', 'Fecha de solicitud de avaluo', 'Fecha de visita',
                        'Estatus','Fecha concluido', 'Seguro de calidad','Valor concluido', 'Costo', 'Fecha enviado a ventas'
                    ]);

                    $sheet->setColumnFormat(array(
                        'J' => '$#,##0.00',
                        'P' => '$#,##0.00',
                        'Q' => '$#,##0.00',
                    ));


                    $sheet->cells('A1:R1', function ($cells) {
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

                    foreach($avaluos as $index => $avaluo) {
                        $cont++;

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($avaluo->fecha_solicitud);
                        $avaluo->fecha_solicitud = $fecha1->formatLocalized('%d de %B de %Y');

                        $fecha2 = new Carbon($avaluo->fecha_ava_sol);
                        $avaluo->fecha_ava_sol = $fecha2->formatLocalized('%d de %B de %Y');

                        $fecha3 = new Carbon($avaluo->visita_avaluo);
                        $avaluo->visita_avaluo = $fecha3->formatLocalized('%d de %B de %Y');

                        $fecha4 = new Carbon($avaluo->fecha_concluido);
                        $avaluo->fecha_concluido = $fecha4->formatLocalized('%d de %B de %Y');

                        $fecha5 = new Carbon($avaluo->fecha_recibido);
                        $avaluo->fecha_recibido = $fecha5->formatLocalized('%d de %B de %Y');

                        if($avaluo->tipo_credito == 'Alia2' || $avaluo->tipo_credito == 'Fovissste')
                            $avaluo->seguro = 'Si';
                        else{
                            $avaluo->seguro = 'No';
                        }
                        
                        $sheet->row($index+2, [
                            $avaluo->folio, 
                            $avaluo->nombre.' '.$avaluo->apellidos,
                            $avaluo->fraccionamiento, 
                            $avaluo->etapa,
                            $avaluo->manzana,
                            $avaluo->num_lote,
                            $avaluo->modelo,
                            $avaluo->avance.'%',
                            $avaluo->fecha_solicitud,
                            $avaluo->valor_requerido,
                            $avaluo->fecha_ava_sol,
                            $avaluo->visita_avaluo,
                            $avaluo->status,
                            $avaluo->fecha_concluido,
                            $avaluo->seguro,
                            $avaluo->resultado,
                            $avaluo->costo,
                            $avaluo->fecha_recibido

                        ]);	
                    }
                    $num='A1:R' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
                }
        )->download('xls');
    }

    public function setFechaSolicitud(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avaluo_id = $request->avaluoId;

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_ava_sol = $request->fecha_ava_sol;
        $avaluo->save();
    }

    public function setFechaPago(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avaluo_id = $request->avaluoId;

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_pago = $request->fecha_pago;
        $avaluo->save();
    }

    public function setFechaConcluido(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avaluo_id = $request->avaluoId;

        try{
            DB::beginTransaction();
            if($request->costo >0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $request->id;
                $gasto->concepto = 'Avaluo';
                $gasto->costo = $request->costo;
                $gasto->fecha = $request->fecha_concluido;
                $gasto->observacion = '';
                $gasto->save();
            }

            $avaluo=Avaluo::findOrFail($avaluo_id);
            $avaluo->costo = $request->costo;
            $avaluo->fecha_concluido = $request->fecha_concluido;
            $avaluo->resultado = $request->resultado;
            $avaluo->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo = $contrato->saldo + $request->costo;
            $contrato->save(); 

            if($request->observacion != ''){
                $observacion = new Obs_expediente();
                $observacion->contrato_id = $request->id;
                $observacion->observacion = $request->observacion;
                $observacion->usuario = Auth::user()->usuario;
                $observacion->save();
            }
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function updateFechaConcluido(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();

            $costo_ant = 0;
            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $costo_ant = $avaluo->costo;
            $contrato_id = $avaluo->contrato_id;
            $avaluo->costo = $request->costo;
            $avaluo->fecha_concluido = $request->fecha_concluido;
            $avaluo->resultado = $request->resultado;
            $avaluo->save();

            if($request->costo > 0 && $costo_ant != 0){
                $gasto = Gasto_admin::findOrFail($request->gasto_id);
                $costo_ant = $gasto->costo;
                $gasto->costo = $request->costo;
                $gasto->fecha = $request->fecha_concluido;
                $gasto->save();
            }
            elseif($request->costo > 0 && $costo_ant == 0){
                $gasto = new Gasto_admin();
                $gasto->contrato_id = $contrato_id;
                $gasto->concepto = 'Avaluo';
                $gasto->costo = $request->costo;
                $gasto->fecha = $request->fecha_concluido;
                $gasto->observacion = '';
                $gasto->save();
            }

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = $contrato->saldo - $costo_ant + $request->costo;
            $contrato->save(); 

            if($request->observacion != ''){
                $observacion = new Obs_expediente();
                $observacion->contrato_id = $contrato_id;
                $observacion->observacion = $request->observacion;
                $observacion->usuario = Auth::user()->usuario;
                $observacion->save();
            }
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function enviarVentas(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avaluo_id = $request->id;
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $avaluo=Avaluo::findOrFail($avaluo_id);
        $avaluo->fecha_recibido = $hoy;
        $numContrato = $avaluo->contrato_id;
        $avaluo->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "Avaluo finalizado para el contrato #" . $numContrato;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Avaluo Finalizado'
                    ]
                ];

                $users = User::select('id')->where('rol_id','=','1')
                    ->orWhere('rol_id','=','6')
                    ->orWhere('rol_id','=','8')->get();

                foreach($users as $notificar){
                    User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloAceptado));
                }
    }

    public function formSubmitAvaluo(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $pdfAnterior = Avaluo::select('pdf', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($pdfAnterior->isEmpty() == 1) {
            $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
            $moved =  $request->pdf->move(public_path('/files/avaluos'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Avaluo::findOrFail($request->id);
                $predial->pdf = $fileName;
                $predial->save(); //Insert

            }

            return back();
        } else {
            $pathAnterior = public_path() . '/files/avaluos/' . $pdfAnterior[0]->pdf;
            File::delete($pathAnterior);

            $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
            $moved =  $request->pdf->move(public_path('/files/avaluos'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Avaluo::findOrFail($request->id);
                $predial->pdf = $fileName;
                $predial->save(); //Insert

            }

            return back();
        }
    }

    public function downloadFile($fileName)
    {

        $pathtoFile = public_path() . '/files/avaluos/' . $fileName;
        return response()->download($pathtoFile);
    }
}
