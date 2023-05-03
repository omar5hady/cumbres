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
    //Esta funcion crea una solicitud de detalles  para el contratista
    public function storeSolicitud(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');


        try{ // se empieza
            DB::beginTransaction();
            $solicitud = new Solic_detalle(); // se crea un nuevo registro en la tabla de solicitudes
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
                $descripcion = new Descripcion_detalle(); // se crea nuevo regitro de la descripcion del detalle
                $descripcion->solicitud_id = $solicitud->id;
                $descripcion->detalle_id = $det['id_detalle'];
                $descripcion->garantia = $det['garantia'];
                $descripcion->observacion = $det['observacion'];
                $descripcion->detalle = $det['detalle'];
                $descripcion->subconcepto = $det['subconcepto'];
                $descripcion->general = $det['general'];
                $descripcion->save();
            }

            // se crea la notificacion para el contratista
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
            DB::rollBack(); // en caso de error se regresa al estado inicial
        }
    }

    // En esta funcion se hace una peticion de la informacion general de las solicitudes
    public function indexSolicitudes(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $criterio = $request->criterio;
        $fecha1 = $request->b_fecha1;
        $fecha2 = $request->b_fecha2;

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
                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,
                    'solic_detalles.obs_gen',
                    'solic_detalles.fecha_program','solic_detalles.hora_program');

        // busqueda por criterios seleccionados

        if($buscar != ''){
            switch($criterio){
                case 'contratos.id':{
                    $solicitudes = $solicitudes
                        ->where($criterio,'=',$buscar);
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                        $solicitudes = $solicitudes->where($criterio,'=',$buscar);
                        if($etapa != '')
                            $solicitudes = $solicitudes->where('lotes.etapa_id','=',$etapa);
                        if( $manzana != '' )
                            $solicitudes = $solicitudes->where('lotes.manzana','=',$manzana);
                        if($lote != '')
                            $solicitudes = $solicitudes->where('lotes.num_lote','=',$lote);
                    break;
                }
                default:{
                    $solicitudes = $solicitudes->where($criterio,'like','%'.$buscar.'%');
                    break;
                }
            }
        }

        if($fecha1 != '')
            $solicitudes = $solicitudes->whereBetween('solic_detalles.created_at',[$fecha1.' 00:00:01',$fecha2.' 23:59:59']);

        if($status != '')
            $solicitudes = $solicitudes->where('solic_detalles.status','=',$status);


        $solicitudes = $solicitudes->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->paginate(8);

        foreach($solicitudes as $solic){
            $detalles = Descripcion_detalle::select('fecha_concluido')
                ->where('solicitud_id','=',$solic->id)
                ->where('fecha_concluido','!=', NULL)
                ->get();

            if(sizeof($detalles))
                $solic->fecha_concluido = $detalles[0]->fecha_concluido;
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

    //Crea el archivo Excel de la consulta realizada
    public function excelSolicitudes(Request $request){
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $criterio = $request->criterio;

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
                    'solic_detalles.costo','contratistas.nombre', 'solic_detalles.created_at' ,
                    'solic_detalles.obs_gen',
                    'solic_detalles.fecha_program','solic_detalles.hora_program');

        if($buscar != ''){
            switch($criterio){
                case 'contratos.id':{
                    $solicitudes = $solicitudes
                        ->where($criterio,'=',$buscar);
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                        $solicitudes = $solicitudes->where($criterio,'=',$buscar);
                        if($etapa != '')
                            $solicitudes = $solicitudes->where('lotes.etapa_id','=',$etapa);
                        if( $manzana != '' )
                            $solicitudes = $solicitudes->where('lotes.manzana','=',$manzana);
                        if($lote != '')
                            $solicitudes = $solicitudes->where('lotes.num_lote','=',$lote);
                    break;
                }
                default:{
                    $solicitudes = $solicitudes->where($criterio,'like','%'.$buscar.'%');
                    break;
                }
            }
        }

        if($status != '')
            $solicitudes = $solicitudes->where('solic_detalles.status','=',$status);

        $solicitudes = $solicitudes->orderBy('solic_detalles.status','asc')
                            ->orderBy('solic_detalles.created_at','asc')
                            ->get();

        // retorna el archivo excel
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



    // Esta funcion realiza la consulta de la informacion de las descripciones
    public function indexDescripciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $detalles = Descripcion_detalle::select('general','subconcepto','detalle','garantia','id','costo',
                                'observacion','fecha_concluido','resultado','revisado','solicitud_id')
                ->where('solicitud_id','=',$request->id)->get();

        return ['detalles' => $detalles];
    }

    // Esta funcion crea un documento PDF con la informacion de la solicitud  relacionada al contrato del cliente
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

            // se les da formato a las fechas para el documento
            $fecha_entrega_real = new Carbon($solicitud[0]->fecha_entrega_real);
            $solicitud[0]->fecha_entrega_real = $fecha_entrega_real->formatLocalized('%d de %B de %Y');

            $fecha = new Carbon($solicitud[0]->fecha);
            $solicitud[0]->fecha = $fecha->formatLocalized('%d de %B de %Y');

            $solicitud[0]->celular = '('.substr($solicitud[0]->celular, 0, 3).') '.substr($solicitud[0]->celular, 3, 3).'-'.substr($solicitud[0]->celular,6);




        $pdf = \PDF::loadview('pdf.DocsPostVenta.SolicitudDetalle', ['solicitud' => $solicitud , 'detalles' => $detalles]);
                    return $pdf->stream('SolicitudDetalle.pdf');
    }

    //Esta funcion hace la consulta de las solicitudes que se encuentran en el modulo de contratista
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

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 12){ // Filtros de busqueda para Administrador y Postventa
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
        else{  // filtros de busqueda para el Contratista
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

    // En esta funcion se obtiene la descripcion de detalles  para el contratista
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
                                        ->where('descripcion_detalles.solicitud_id','=',$solicitudID) // filtro principal de la solicitud
                                        ->orderBy('descripcion_detalles.fecha_concluido','ASC')
                                        ->get();

            return ['detalles' => $detalles];

    }

    // En esta funcion se actualiza el costo total de la solicitud
    // se actualiza en la descripcion y en la solicitud
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

    // funcion para finalizar la solicitud
    public function finalizarReporte(Request $request){
        $solicitud = Solic_detalle::findOrFail($request->solicitud_id);
        $solicitud->status = 3; //
        $solicitud->save();
    }

    // Funcion para cambiar la fecha de finalizado de la solicitud
    public function updateFechaConcluido(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $costo = Descripcion_detalle::findOrFail($request->id);
        $costo->fecha_concluido = $request->fecha_concluido;
        $costo->revisado = 0; // se regresa su status para que sea revisado
        $costo->save();

        $total = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)->count();
        $totalTerminados = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)
            ->where('fecha_concluido','!=',NULL)->where('revisado','=',2)->count();

        if($total == $totalTerminados){ // revisa si todas la solicitudes estan terminadas
            $totalSolicitud = Solic_detalle::findOrFail($request->solicitud_id);
            $totalSolicitud->status = 2; // se finaliza la solicitud
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

    //En esta funcion se actualiza el status de la descripcion de detalle
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

        // slecciona la
        $total = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)->count(); //cuenta el total de las solicitudes
        $totalTerminados = Descripcion_detalle::where('solicitud_id','=',$request->solicitud_id)
            ->where('fecha_concluido','!=',NULL)->where('revisado','=',2)->count(); //cuenta el total de solicitudes finalizadas

        if($total == $totalTerminados){ // verifica si todas las solicitudes estan finalizadas
            $totalSolicitud = Solic_detalle::findOrFail($request->solicitud_id);
            $totalSolicitud->status = 2; // en la solicitud general la cambia a de status a finalizado

            // crea la notificacion
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

    // En esta funcion actualiza la hora de disponibilidad del cliente
    // y se guarda en la solicitud
    public function updateHora(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_detalle::findOrFail($request->id);
        $solicitud->hora_program = $request->hora_program;
        $solicitud->save();

    }

    //En esta funcion actualiza la fecha de cliente para la solicitud
    // se guarda la solicitud
    public function updateFecha(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $solicitud = Solic_detalle::findOrFail($request->id);
        $solicitud->fecha_program = $request->fecha_program;
        $solicitud->status = 1; // status pendiente

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

    // En esta funcion se crea el documento con la inormacion de la solicitud de status terminado
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

            foreach($detalles as $detalle){ // se le da formato a las fechas para cada solicitud individual
                 $fecha = new Carbon($detalle->fecha_concluido);
                 $detalle->fecha_concluido = $fecha->formatLocalized('%d de %B de %Y');
            }

            // Se les  da formato a las fechas
            $fecha_entrega_real = new Carbon($solicitud[0]->fecha_entrega_real);
            $solicitud[0]->fecha_entrega_real = $fecha_entrega_real->formatLocalized('%d de %B de %Y');

            $fecha = new Carbon($solicitud[0]->fecha);
            $solicitud[0]->fecha = $fecha->formatLocalized('%d de %B de %Y');

            //Formato con parentesis al numero de celular
            $solicitud[0]->celular = '('.substr($solicitud[0]->celular, 0, 3).') '.substr($solicitud[0]->celular, 3, 3).'-'.substr($solicitud[0]->celular,6);




        $pdf = \PDF::loadview('pdf.DocsPostVenta.ReporteConclusion', ['solicitud' => $solicitud ,'detalles' => $detalles]);
                    return $pdf->stream('ReporteConclusion.pdf');
    }

    //En esta funcion crea un archivo excel con toda la informacion de las solicitudes y los proyectos relacionados a cada solicitud
    public function agendaContratista(Request $request){

        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;

        $agenda = Solic_detalle::join('contratos','solic_detalles.contrato_id','=','contratos.id')
                        ->join('contratistas','solic_detalles.contratista_id','=','contratistas.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')

                        ->select('solic_detalles.id','lotes.num_lote','lotes.sublote','fraccionamientos.nombre as proyecto','etapas.num_etapa',
                            'contratistas.nombre as contratista','solic_detalles.cliente','solic_detalles.fecha_program',
                            'solic_detalles.hora_program', 'lotes.calle','lotes.numero','lotes.interior','lotes.emp_constructora',
                            'lotes.manzana'
                        )
                        // filtros por contratista, proyecto y margen de fechas
                        ->where('fraccionamientos.id','=',$request->proyecto);
                        if($request->etapa != '')
                            $agenda  = $agenda->where('etapas.id','=',$request->etapa);
                        $agenda  = $agenda->where('contratistas.id','=',$request->contratista)
                        ->whereBetween('solic_detalles.fecha_program', [$fecha1, $fecha2])

                        ->orderBy('fecha_program','asc')
                        ->orderBy('hora_program','asc')

                        ->get();

     //return ['agenda'=>$agenda];

        // funcion para crear el documento
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
                        $direccion = $lote->calle.' No. '.$lote->numero;
                        if($lote->sublote != NULL){
                            $direccion = $lote->calle.' No. '.$lote->numero. 'Int. '.$lote->sublote;
                        }

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
