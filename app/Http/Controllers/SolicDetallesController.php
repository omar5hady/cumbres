<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solic_detalle;
use App\Descripcion_detalle;
use App\User;
use App\Notifications\NotifyAdmin;
use Auth;
use DB;
use Excel;
use PHPExcel_Worksheet_Drawing;
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
            $solicitud->obs_gen = $request->obs_gen;
            
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

        $query = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                    'solic_detalles.lunes','solic_detalles.martes',
                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,
                    'solic_detalles.obs_gen',
                    'solic_detalles.fecha_program','solic_detalles.hora_program');

        if($status == ''){
            if($buscar == ''){
                $solicitudes = $query;
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = $query
                            ->where($criterio,'like','%'.$buscar.'%');
                        break;
                    }
                }
            }
           
        }
        else{
            if($buscar == ''){
                $solicitudes = $query
                    ->where('solic_detalles.status','=',$status);
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = $query
                            ->where($criterio,'like','%'.$buscar.'%')
                            ->where('solic_detalles.status','=',$status);
                        break;
                    }
                }
            }
        }

        $solicitudes = $solicitudes->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);

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

    public function excelSolicitudes(Request $request){
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $criterio = $request->criterio;

        $query = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->select('solic_detalles.id','contratos.id as folio','creditos.fraccionamiento',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo',
                    'solic_detalles.lunes','solic_detalles.martes',
                    'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                    'solic_detalles.sabado','solic_detalles.nom_contrato',
                    'solic_detalles.cliente','solic_detalles.celular','solic_detalles.status',
                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,
                    'solic_detalles.obs_gen',
                    'solic_detalles.fecha_program','solic_detalles.hora_program');

        if($status == ''){
            if($buscar == ''){
                $solicitudes = $query;
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = $query
                            ->where($criterio,'=',$buscar);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = $query
                            ->where($criterio,'like','%'.$buscar.'%');
                        break;
                    }
                }
            }
           
        }
        else{
            if($buscar == ''){
                $solicitudes = $query
                    ->where('solic_detalles.status','=',$status);
            }
            else{
                switch($criterio){
                    case 'contratos.id':{
                        $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status);
                        break;
                    }
                    case 'lotes.fraccionamiento_id':{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.manzana','=',$manzana)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.etapa_id','=',$etapa)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        elseif($etapa == '' && $manzana == '' && $lote != ''){
                            $solicitudes = $query
                            ->where($criterio,'=',$buscar)
                            ->where('solic_detalles.status','=',$status)
                            ->where('lotes.num_lote','=',$lote);
                        }
                        break;
                    }
                    default:{
                        $solicitudes = $query
                            ->where($criterio,'like','%'.$buscar.'%')
                            ->where('solic_detalles.status','=',$status);
                        break;
                    }
                }
            }
        }

        $solicitudes = $solicitudes->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->get();

        return Excel::create(
            'Solic. de Detalles',
            function ($excel) use ($solicitudes) {
                $excel->sheet('Solicitudes', function ($sheet) use ($solicitudes) {

                    $sheet->row(1, [
                        'Folio.', 'Cliente', 'Proyecto', 'Etapa',
                        'Manzana', '# Lote', 'Modelo', 'Contratista', 'Fecha de reporte',
                        'Disponibilidad', 'Costo', 'Status'
                    ]);


                    $sheet->cells('A1:L1', function ($cells) {
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
                    ));

                    $cont = 1;

                    foreach ($solicitudes as $index => $lote) {
                        $cont++;
                        $disponibilidad = '';
                        $status = '';

                        if($lote->status == '0')
                            $status = 'Pendiente';
                        if($lote->status == '1')
                            $status = 'En proceso';
                        if($lote->status == '2')
                            $status = 'Concluido';

                        if($lote->lunes == 1) $disponibilidad = $disponibilidad.'  Lunes';
                        if($lote->martes == 1) $disponibilidad = $disponibilidad.'  Martes';
                        if($lote->miercoles == 1) $disponibilidad = $disponibilidad.'  Miercoles';
                        if($lote->jueves == 1) $disponibilidad = $disponibilidad.'  Jueves';
                        if($lote->viernes == 1) $disponibilidad = $disponibilidad.'  Viernes';
                        if($lote->sabado == 1) $disponibilidad = $disponibilidad.'  Sabado';

                        //$tiempo = new Carbon($lote->created_at);
                                //    $lote->created_at = $tiempo->formatLocalized('%d de %B de %Y');

                        $sheet->row($index + 2, [
                            $lote->id,
                            $lote->cliente,
                            $lote->fraccionamiento,
                            $lote->etapa,
                            $lote->manzana,
                            $lote->num_lote,
                            $lote->modelo,
                            $lote->nombre,
                            $lote->created_at,
                            $disponibilidad,
                            $lote->costo,
                            $status
                        ]);
                    }
                    $num = 'A1:L' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');
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
                                    'solic_detalles.obs_gen', 'lotes.emp_constructora', 'lotes.emp_terreno',
                                    'solic_detalles.horario','entregas.fecha_entrega_real','lotes.calle', 'lotes.numero')
                            ->where('solic_detalles.id','=',$id)
                            ->get();

            $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','fecha_concluido','observacion')->where('solicitud_id','=',$id)->get();

        

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

        $query = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
            ->select('contratos.id as folio','solic_detalles.id as solicitudID','solic_detalles.cliente',
                 'solic_detalles.dias_entrega','solic_detalles.lunes','solic_detalles.martes',
                 'solic_detalles.miercoles','solic_detalles.jueves','solic_detalles.viernes',
                 'solic_detalles.sabado','creditos.fraccionamiento as proyecto','creditos.etapa',
                 'creditos.manzana','creditos.num_lote','creditos.modelo','contratistas.nombre as contratista','solic_detalles.status',
                 'solic_detalles.created_at','lotes.fraccionamiento_id','lotes.etapa_id',
                 'solic_detalles.fecha_program','solic_detalles.hora_program');

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 12){
            if($buscar == ''){
                $contratos = $query
                         ->where('contratos.entregado','=',1)
                         ->where('solic_detalles.fecha_program','!=',NULL);
            }else{
                switch($criterio){
                    case 'lotes.fraccionamiento_id': {
                        if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                            $contratos = $query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa)
                                        ->where('creditos.manzana','=',$b_manzana)
                                        ->where('creditos.num_lote','=',$b_lote);
                        }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                            $contratos = $query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa)
                                        ->where('creditos.manzana','=',$b_manzana);
                        }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                            $contratos =$query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('lotes.etapa_id','=',$b_etapa);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                            $contratos = $query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                            $contratos = $query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('creditos.num_lote','=',$b_lote);
                        }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                            $contratos = $query
                                        ->where('contratos.entregado','=',1)
                                        ->where('solic_detalles.fecha_program','!=',NULL)
                                        ->where($criterio,'=',$buscar)
                                        ->where('creditos.manzana','=',$b_manzana);
                        }
                        break;
                    }
                    default: {
                        $contratos = $query
                                 ->where('contratos.entregado','=',1)
                                 ->where('solic_detalles.fecha_program','!=',NULL)
                                 ->where($criterio,'=',$buscar);
                        break;
                    }
                }
            }
        }
        else{
            if($status == ''){
                if($buscar == ''){
                    $contratos = $query
                             ->where('contratos.entregado','=',1)
                             ->where('solic_detalles.fecha_program','!=',NULL)
                             ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                             ->where('solic_detalles.status','!=',2);
                }else{
                    switch($criterio){
                        case 'lotes.fraccionamiento_id': {
                            if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->where('creditos.num_lote','=',$b_lote);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.num_lote','=',$b_lote);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where('solic_detalles.status','!=',2)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.manzana','=',$b_manzana);
                            }
                            break;
                        }
                        default: {
                            $contratos = $query
                                     ->where('contratos.entregado','=',1)
                                     ->where('solic_detalles.fecha_program','!=',NULL)
                                     ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                     ->where('solic_detalles.status','!=',2)
                                     ->where($criterio,'=',$buscar);
                            break;
                        }
                    }
                }
            }
            else{
                if($buscar == ''){
                    $contratos = $query
                             ->where('contratos.entregado','=',1)
                             ->where('solic_detalles.fecha_program','!=',NULL)
                             ->where('solic_detalles.status','=',2)
                             ->where('solic_detalles.contratista_id','=',Auth::user()->id);
                }else{
                    switch($criterio){
                        case 'lotes.fraccionamiento_id': {
                            if($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote != '' ){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana)
                                            ->where('creditos.num_lote','=',$b_lote);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa)
                                            ->where('creditos.manzana','=',$b_manzana);
                            }elseif($buscar != '' && $b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('lotes.etapa_id','=',$b_etapa);
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
                                            ->where($criterio,'=',$buscar);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.num_lote','=',$b_lote);
                            }elseif($buscar != '' && $b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                            ->where('contratos.entregado','=',1)
                                            ->where('solic_detalles.fecha_program','!=',NULL)
                                            ->where('solic_detalles.status','=',2)
                                            ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                            ->where($criterio,'=',$buscar)
                                            ->where('creditos.manzana','=',$b_manzana);
                            }
                            break;
                        }
                        default: {
                            $contratos = $query
                                     ->where('contratos.entregado','=',1)
                                     ->where('solic_detalles.fecha_program','!=',NULL)
                                     ->where('solic_detalles.status','=',2)
                                     ->where('solic_detalles.contratista_id','=',Auth::user()->id)
                                     ->where($criterio,'=',$buscar);
                            break;
                        }
                    }
                }
            }
        }

        $contratos = $contratos->where('solic_detalles.status','!=',3)->orderBy('solic_detalles.fecha_program','ASC')
                                ->paginate(10);
        
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

    public function finalizarReporte(Request $request){
        $solicitud = Solic_detalle::findOrFail($request->solicitud_id);
        $solicitud->status = 3;
        $solicitud->save();
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
                                    'solic_detalles.obs_gen','lotes.emp_constructora', 'lotes.emp_terreno',
                                    'solic_detalles.horario','entregas.fecha_entrega_real','lotes.calle', 'lotes.numero')
                            ->where('solic_detalles.id','=',$id)
                            ->get();

            $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','fecha_concluido','observacion')->where('solicitud_id','=',$id)->get();

        

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

    public function agendaContratista(Request $request){

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $agenda = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                        ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')

                        ->select('solic_detalles.id','lotes.num_lote','fraccionamientos.nombre as proyecto','etapas.num_etapa',
                            'contratistas.nombre as contratista','solic_detalles.cliente','solic_detalles.fecha_program',
                            'solic_detalles.hora_program', 'lotes.calle','lotes.numero','lotes.interior','lotes.emp_constructora',
                            'lotes.manzana'
                        )

                        ->where('fraccionamientos.id','=',$request->proyecto)
                        ->where('contratistas.id','=',$request->contratista)
                        ->whereBetween('solic_detalles.fecha_program', [$fecha1, $fecha2])

                        ->orderBy('fecha_program','asc')
                        ->orderBy('hora_program','asc')

                        ->get();

        // return ['agenda'=>$agenda];

        return Excel::create(
            'Agenda contratistas',
            function ($excel) use ($agenda, $fecha1, $fecha2) {
                $excel->sheet('Agenda', function ($sheet) use ($agenda, $fecha1, $fecha2) {

                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
                $sheet->mergeCells('A4:E4');
                $sheet->mergeCells('A5:E5');
                
                
                $sheet->setSize('A1', 40, 60);
                $sheet->setSize('B1', 30, 60);
                $sheet->setSize('C1', 30, 60);
                $sheet->setSize('D1', 30, 60);
                $sheet->setSize('E1', 30, 60);
                
    
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                if($agenda[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
                if($agenda[0]->emp_constructora == 'CONCRETANIA');
                    $objDrawing->setPath(public_path('img/contratos/logoConcretaniaObra.png')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);

                if($agenda[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $sheet->cell('A1', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(16);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                if($agenda[0]->emp_constructora == 'CONCRETANIA');
                    $sheet->cell('A1', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'CONCRETANIA, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(18);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });

                setlocale(LC_TIME, 'es_MX.utf8');
                $fecha1Aux = new Carbon($fecha1);
                $fecha1Aux  = $fecha1Aux->formatLocalized('%d de %B de %Y');

                $fecha2Aux = new Carbon($fecha1);
                $fecha2Aux  = $fecha2Aux->formatLocalized('%d de %B de %Y');


                
                $sheet->row(2, [
                    'Departamento de Post Venta' 
                ]);

                $sheet->row(3, [
                    'Programa de atencion de detalles ('.$fecha1Aux . ' al '.$fecha2Aux.')' 
                ]);
                $sheet->row(4, [
                    'Contratista '.$agenda[0]->contratista 
                ]);

                $sheet->row(5, [
                    'Proyecto '.$agenda[0]->proyecto 
                ]);

                $sheet->cells('A2:A5', function($cells) {

                    // manipulate the cell
                    $cells->setFontFamily('Arial Narrow');
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                
                });

        
                $sheet->row(7, [
                    'Cliente', 'Direccion','Manzana', 'Fecha programada', 'Hora programada' 
                ]);



                    $sheet->cells('A7:E7', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(12);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $cont = 7;

                    foreach ($agenda as $index => $lote) {
                        $cont++;
                        $fecha = new Carbon($lote->fecha_program);
                        $fecha  = $fecha->formatLocalized("%A %d %B %Y");
                        
                        $sheet->row($cont, [
                            $lote->cliente,
                            $lote->calle.' No. '.$lote->numero,
                            $lote->manzana,
                            $fecha,
                            $lote->hora_program,
                            
                        ]);
                    }
                    $num = 'A7:E' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');

    }

}
