<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solic_detalle;
use App\Descripcion_detalle;
use App\User;
use App\Notifications\NotifyAdmin;
use Auth;
use DB;
use Carbon\Carbon;

class SolicDetallesController extends Controller
{
    public function storeSolicitud(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        
 
        try{
            DB::beginTransaction(); 
            $solicitud = new Solic_detalle();
            $solicitud->contrato_id = $request->folio;
            $solicitud->contratista_id = $request->contratista_id;
            $solicitud->cliente = $request->cliente;
            $solicitud->dias_entrega = $request->dias_entrega;
            $solicitud->lunes = $request->lunes;
            $solicitud->martes = $request->martes;
            $solicitud->miercoles = $request->miercoles;
            $solicitud->jueves = $request->jueves;
            $solicitud->viernes = $request->viernes;
            $solicitud->sabado = $request->sabado;
            $solicitud->horario = $request->horario;
            $solicitud->celular = $request->celular;
            
            $solicitud->save();
 
            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
 
            foreach($detalles as $ep=>$det)
            {
                $descripcion = new Descripcion_detalle();
                $descripcion->solicitud_id = $solicitud->id;
                $descripcion->detalle_id = $det['id_detalle'];
                $descripcion->garantia = $det['garantia'];
                $descripcion->observacion = $det['observacion'];
                $descripcion->detalle = $det['detalle'];
                $descripcion->subconcepto = $det['subconcepto'];
                $descripcion->general = $det['general'];
                $descripcion->save();
            }      
            
            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha creado una nueva solicitud de detalles';
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva solicitud'
                    ]
                ];

                    User::findOrFail($request->contratista_id)->notify(new NotifyAdmin($arregloAceptado));
 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function indexSolicitudes(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $criterio = $request->criterio;

        if($status == ''){
            if($buscar == ''){
                $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                    ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                            'solic_detalles.lunes','solic_detalles.martes',
                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                            'solic_detalles.sabado','solic_detalles.nom_contrato',
                            'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                            'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                    ->orderBy('solic_detalles.status','asc')
                    ->orderBy('solic_detalles.created_at','asc')
                    ->paginate(8);
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,'solic_detalles.fecha_program','solic_detalles.hora_program')
                            ->where($criterio,'like','%'.$buscar.'%')
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        break;
                    }
                }
            }
           
        }
        else{
            if($buscar == ''){
                $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                    ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                            'solic_detalles.lunes','solic_detalles.martes',
                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                            'solic_detalles.sabado','solic_detalles.nom_contrato',
                            'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                            'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                    ->where('solic_detalles.status','=',$status)
                    ->orderBy('solic_detalles.status','asc')
                    ->orderBy('solic_detalles.created_at','asc')
                    ->paginate(8);
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.num_lote','=',$lote)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.lunes','solic_detalles.martes',
                                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at')
                            ->where($criterio,'like','%'.$buscar.'%')
                            ->where('solic_detalles.status','=',$status)
                            ->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);
                        break;
                    }
                }

            }
            
        }
        

        return [
            'pagination' => [
                'total'         => $solicitudes->total(),
                'current_page'  => $solicitudes->currentPage(),
                'per_page'      => $solicitudes->perPage(),
                'last_page'     => $solicitudes->lastPage(),
                'from'          => $solicitudes->firstItem(),
                'to'            => $solicitudes->lastItem(),
            ], 'solicitudes' => $solicitudes
        ];
    }

    public function indexDescripciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','id','costo',
                                'observacion','fecha_concluido','resultado','revisado','solicitud_id')
                ->where('solicitud_id','=',$request->id)->get();

        return ['detalles' => $detalles];
    }

    public function reportePDF($id){

        $solicitud = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('entregas','contratos.id','=','entregas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.cliente','solic_detalles.celular',
                                    'contratistas.nombre', DB::raw('DATE(solic_detalles.created_at) as fecha'),
                                    'solic_detalles.lunes','solic_detalles.martes','solic_detalles.miercoles',
                                    'solic_detalles.jueves','solic_detalles.viernes','solic_detalles.sabado',
                                    'solic_detalles.horario','entregas.fecha_entrega_real','lotes.calle', 'lotes.numero')
                            ->where('solic_detalles.id','=',$id)
                            ->get();

            $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','fecha_concluido')->where('solicitud_id','=',$id)->get();

        

            setlocale(LC_TIME, 'es_MX.utf8');


            $fecha_entrega_real = new Carbon($solicitud[0]->fecha_entrega_real);
            $solicitud[0]->fecha_entrega_real = $fecha_entrega_real->formatLocalized('%d de %B de %Y');

            $fecha = new Carbon($solicitud[0]->fecha);
            $solicitud[0]->fecha = $fecha->formatLocalized('%d de %B de %Y');

            $solicitud[0]->celular = '('.substr($solicitud[0]->celular, 0, 3).') '.substr($solicitud[0]->celular, 3, 3).'-'.substr($solicitud[0]->celular,6);
            
            
        

        $pdf = \PDF::loadview('pdf.DocsPostVenta.SolicitudDetalle', ['solicitud' => $solicitud , 'detalles' => $detalles]);
                    return $pdf->stream('SolicitudDetalle.pdf');
    }

    public function indexContratista(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
        $status = $request->status;

        if(Auth::user()->rol_id == 1){
            if($buscar == ''){
                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                         'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                         'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                         'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                         'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                         'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                         ->where('contratos.entregado','=',1)
                         ->where('solic_detalles.fecha_program','!=',NULL)
                         ->orderBy('solic_detalles.fecha_program','ASC')
                         ->paginate(10);
            }else{
                switch($criterio){
                    case 'lotes.fraccionamiento_id': {
                        if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa)
                                        ->where('creditos.manzana','=',$b_manzana)
                                        ->where('creditos.num_lote','=',$b_lote)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa)
                                        ->where('creditos.manzana','=',$b_manzana)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('creditos.num_lote','=',$b_lote)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                        'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                        'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                        'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                        'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('creditos.manzana','=',$b_manzana)
                                        ->orderBy('solic_detalles.fecha_program','ASC')
                                        ->paginate(10);
                        }
                        break;
                    }
                    default: {
                        $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                        ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                 'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                 'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                 'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                 'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                 'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                 ->where('contratos.entregado','=',1)
                                 ->where('solic_detalles.fecha_program','!=',NULL)
                                 ->where($criterio,'=',$buscar)
                                 ->orderBy('solic_detalles.fecha_program','ASC')
                                 ->paginate(10);
                        break;
                    }
                }
            }
        }
        else{
            if($status == ''){
                if($buscar == ''){
                    $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                    ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                             'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                             'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                             'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                             'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                             'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                             ->where('contratos.entregado','=',1)
                             ->where('solic_detalles.fecha_program','!=',NULL)
                             ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                             ->where('solic_detalles.status','!=',2)
                             ->orderBy('solic_detalles.fecha_program','ASC')
                             ->paginate(10);
                }else{
                    switch($criterio){
                        case 'lotes.fraccionamiento_id': {
                            if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }
                            break;
                        }
                        default: {
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                     'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                     'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                     'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                     'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                     'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                     ->where('contratos.entregado','=',1)
                                     ->where('solic_detalles.fecha_program','!=',NULL)
                                     ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                     ->where('solic_detalles.status','!=',2)
                                     ->where($criterio,'=',$buscar)
                                     ->orderBy('solic_detalles.fecha_program','ASC')
                                     ->paginate(10);
                            break;
                        }
                    }
                }
            }
            else{
                if($buscar == ''){
                    $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                    ->join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                    ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                             'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                             'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                             'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                             'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                             'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                             ->where('contratos.entregado','=',1)
                             ->where('solic_detalles.fecha_program','!=',NULL)
                             ->where('solic_detalles.status','=',2)
                             ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                             ->orderBy('solic_detalles.fecha_program','ASC')
                             ->paginate(10);
                }else{
                    switch($criterio){
                        case 'lotes.fraccionamiento_id': {
                            if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.num_lote','=',$b_lote)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                                ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                            'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                            'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                            'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                            'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                            'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->orderBy('solic_detalles.fecha_program','ASC')
                                            ->paginate(10);
                            }
                            break;
                        }
                        default: {
                            $contratos = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                                     'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                                     'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                                     'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                                     'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                                     'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id','solic_detalles.fecha_program','solic_detalles.hora_program')
                                     ->where('contratos.entregado','=',1)
                                     ->where('solic_detalles.fecha_program','!=',NULL)
                                     ->where('solic_detalles.status','=',2)
                                     ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                     ->where($criterio,'=',$buscar)
                                     ->orderBy('solic_detalles.fecha_program','ASC')
                                     ->paginate(10);
                            break;
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

    public function indexDetallesContratista(Request $request){
        if(!$request->ajax())return redirect('/');
        $solicitudID = $request->solicitud_id;
        $detalles = Descripcion_detalle::join('solic_detalles','descripcion_detalles.solicitud_id','=','solic_detalles.id')
                                        ->join('contratos','solic_detalles.contrato_id','=','contratos.id')
                                        ->join('creditos','contratos.id','=','creditos.id')
                                        ->select('descripcion_detalles.general','descripcion_detalles.subconcepto','descripcion_detalles.detalle',
                                                 'descripcion_detalles.costo','descripcion_detalles.fecha_concluido','descripcion_detalles.observacion',
                                                 'descripcion_detalles.garantia','descripcion_detalles.solicitud_id','solic_detalles.cliente',
                                                 'creditos.fraccionamiento as proyecto','creditos.etapa','creditos.manzana','creditos.num_lote',
                                                 'creditos.modelo',DB::raw('DATE(descripcion_detalles.created_at) AS fechaCreacion'),
                                                 'descripcion_detalles.id','descripcion_detalles.resultado','descripcion_detalles.revisado')
                                        ->where('descripcion_detalles.solicitud_id','=',$solicitudID)
                                        ->orderBy('descripcion_detalles.fecha_concluido','ASC')
                                        ->get();

            return ['detalles' => $detalles];

    }

    public function updateCosto(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $costo = Descripcion_detalle::findOrFail($request->id);
        $costo->costo = $request->costo;
        $costo->save();

        $total = Descripcion_detalle::select(DB::raw('SUM(costo) as totalCosto'))->where('solicitud_id','=',$request->solicitud_id)->get();
        $totalSolicitud = Solic_detalle::findOrFail($request->solicitud_id);
        $totalSolicitud->costo = $total[0]->totalCosto;
        $totalSolicitud->save();

    }

    public function updateFechaConcluido(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $costo = Descripcion_detalle::findOrFail($request->id);
        $costo->fecha_concluido = $request->fecha_concluido;
        $costo->revisado = 0;
        $costo->save();

        $total = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)->count();
        $totalTerminados = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)
            ->where('fecha_concluido','!=',NULL)->where('revisado','=',2)->count();

        if($total == $totalTerminados){
            $totalSolicitud = Solic_detalle::findOrFail($request->solicitud_id);
            $totalSolicitud->status = 2;
            $totalSolicitud->save();
        }
        
        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Nuevo detalle concluido, realizar la revision';
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Detalle concluido'
                    ]
                ];

                    User::findOrFail(25694)->notify(new NotifyAdmin($arregloAceptado));


    }

    public function updateResultado(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $costo = Descripcion_detalle::findOrFail($request->id);
        if($request->resultado == 0){
            $costo->fecha_concluido = NULL;
            $costo->revisado = 1;
            $costo->resultado = $request->comentario;
        }
        else{
            $costo->revisado = 2;
            $costo->resultado = '';
        }
        

        $costo->save();

        $total = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)->count();
        $totalTerminados = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)
            ->where('fecha_concluido','!=',NULL)->where('revisado','=',2)->count();

        if($total == $totalTerminados){
            $totalSolicitud = Solic_detalle::findOrFail($request->solicitud_id);
            $totalSolicitud->status = 2;

            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'La solicitud No.'.$request->solicitud_id.' ha sido finalizada';
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Solicitud finalizada'
                    ]
                ];

                    User::findOrFail($totalSolicitud->contratista_id)->notify(new NotifyAdmin($arregloAceptado));
            $totalSolicitud->save();
        }
        

    }


    public function updateHora(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_detalle::findOrFail($request->id);
        $solicitud->hora_program = $request->hora_program;
        $solicitud->save();

    }

    public function updateFecha(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_detalle::findOrFail($request->id);
        $solicitud->fecha_program = $request->fecha_program;
        $solicitud->status = 1;

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha programado la fecha de visita para la solicitud #.'.$request->id;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Fecha programada'
                    ]
                ];

                    User::findOrFail($solicitud->contratista_id)->notify(new NotifyAdmin($arregloAceptado));

        $solicitud->save();

    }

    public function reporteConclusionPDF($id){

        $solicitud = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                            ->join('entregas','contratos.id','=','entregas.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                                    'solic_detalles.cliente','solic_detalles.celular',
                                    'contratistas.nombre', DB::raw('DATE(solic_detalles.created_at) as fecha'),
                                    'solic_detalles.lunes','solic_detalles.martes','solic_detalles.miercoles',
                                    'solic_detalles.jueves','solic_detalles.viernes','solic_detalles.sabado',
                                    'solic_detalles.horario','entregas.fecha_entrega_real','lotes.calle', 'lotes.numero')
                            ->where('solic_detalles.id','=',$id)
                            ->get();

            $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','fecha_concluido')->where('solicitud_id','=',$id)->get();

        

            setlocale(LC_TIME, 'es_MX.utf8');
            
            foreach($detalles as $detalle){
                 $fecha = new Carbon($detalle->fecha_concluido);
                 $detalle->fecha_concluido = $fecha->formatLocalized('%d de %B de %Y');
            }
            

            $fecha_entrega_real = new Carbon($solicitud[0]->fecha_entrega_real);
            $solicitud[0]->fecha_entrega_real = $fecha_entrega_real->formatLocalized('%d de %B de %Y');

            $fecha = new Carbon($solicitud[0]->fecha);
            $solicitud[0]->fecha = $fecha->formatLocalized('%d de %B de %Y');

            $solicitud[0]->celular = '('.substr($solicitud[0]->celular, 0, 3).') '.substr($solicitud[0]->celular, 3, 3).'-'.substr($solicitud[0]->celular,6);
            

    

        $pdf = \PDF::loadview('pdf.DocsPostVenta.ReporteConclusion', ['solicitud' => $solicitud ,'detalles' => $detalles]);
                    return $pdf->stream('ReporteConclusion.pdf');
    }

}
