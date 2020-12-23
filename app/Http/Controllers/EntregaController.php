<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrega;
use App\Obs_entrega;
use App\Obs_exp;
use DB;
use Auth;
use App\Expediente;
use App\lote;
use Excel;
use Carbon\Carbon;
use App\Contrato;
use App\Credito;
use App\Etapa;
use App\Fraccionamiento;
use NumerosEnLetras;
use App\User;
use App\Notifications\NotifyAdmin;
use App\Ini_obra;
use App\Solic_equipamiento;
use App\Obs_expediente;

class EntregaController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();

            $buscaEntrega = Entrega::select('id')->where('id','=',$request->id)->count();
            if($buscaEntrega==0){
                $entrega = new Entrega();
                $entrega->id = $request->id;
                $entrega->save();
            }
            

            $contrato = Contrato::findOrFail($request->id);
            if($contrato->integracion == 1){
                $expediente = Expediente::findOrFail($request->id);
                if($expediente->fecha_firma_esc != NULL){
                    $expediente->postventa = 1;
                }
                $expediente->save();
            }
            

            $credito = Credito::findOrFail($request->id);
            $lote = $credito->num_lote;
            $proyecto = $credito->fraccionamiento;
            $etapa = $credito->etapa;

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->id;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            $observacion_exp = new Obs_expediente();
            $observacion_exp->contrato_id = $request->id;
            $observacion_exp->observacion = 'Se solicito la entrega de la vivienda';
            $observacion_exp->usuario = Auth::user()->usuario;
            $observacion_exp->save();

            $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha solicitado la entrega del lote: '.$lote. ' del fraccionamiento: '.$proyecto.', etapa: '.$etapa;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva solicitud de entrega'
                    ]
                ];

                // $users = User::select('id')
                //     ->where('entregas','=','1')->get();

                // foreach($users as $notificar){
                //     User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloAceptado));
                // }

                //User::findOrFail(25042)->notify(new NotifyAdmin($arregloAceptado));
                //User::findOrFail(25695)->notify(new NotifyAdmin($arregloAceptado));

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    //{{{{{{{{{{{{{{{{{{{ SECCION PARA ENTREGA DE VIVIENDAS }}}}}}}}}}}}}}}}}}}
        public function indexPendientesExcel(Request $request){
            $criterio = $request->criterio;
            $buscar = $request->buscar;
            $b_etapa = $request->b_etapa;
            $b_manzana = $request->b_manzana;
            $b_lote = $request->b_lote;

            $fecha = Carbon::now();
            $mytime = $fecha->toTimeString();
            $hoy =  $fecha->toDateString();

            $query = Entrega::join('contratos','entregas.id','contratos.id')
                ->leftJoin('expedientes','contratos.id','expedientes.id')
                ->join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id as folio', 
                    'contratos.equipamiento',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'c.celular', 
                    'c.f_nacimiento','c.rfc',
                    'c.homoclave','c.direccion','c.colonia','c.cp',
                    'c.telefono','c.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida',

                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.paquete',
                    'creditos.promocion',
                    'creditos.descripcion_paquete',
                    'creditos.descripcion_promocion',
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
                    'entregas.fecha_program',
                    'entregas.hora_entrega_prog',
                    'entregas.fecha_entrega_real',
                    'entregas.hora_entrega_real',
                    'entregas.revision_previa',
                    DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
            );

            if($buscar == ''){
                $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');

                        break;
                    }

                    case 'entregas.fecha_program':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->whereBetween($criterio, [$buscar, $b_etapa]);

                        break;
                    }

                    case 'contratos.id':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where($criterio, '=', $buscar);

                        break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($request->b_desde == ''){
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
        
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
        
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                        }
                        else{
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta]);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa);
        
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.num_lote', '=', $b_lote);
        
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                        }
                        

                        break;
                    }
                }
            }

            $contratos = $contratos//->whereNotNull('entregas.fecha_program')
                
                ->orderBy('licencias.avance','desc')
                ->orderBy('lotes.fecha_program','desc')
                ->orderBy('lotes.fecha_entrega_obra','desc')
            ->get();

            //return $contratos;
            //$contratos = $contratos->orderBy('licencias.avance','desc')->orderBy('lotes.fecha_entrega_obra','desc')->get();
            
            return Excel::create('Entregas de vivienda', function($excel) use ($contratos){
                    $excel->sheet('Entregas de vivienda', function($sheet) use ($contratos){
                        
                        $sheet->row(1, [
                            '# Ref','Proyecto', 'Etapa', 'Manzana',
                            'Lote','Cliente','Celular', 'Paquete y/o Promocioón', 'Fecha de firma de escrituras',
                            'Fecha entrega programada', 'Hora entrega programada'
                        ]);

                        $sheet->cells('A1:K1', function ($cells) {
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

                        foreach($contratos as $index => $entrega) {
                            $cont++;

                            setlocale(LC_TIME, 'es_MX.utf8');
                            $fecha_firma_esc = new Carbon($entrega->fecha_firma_esc);
                            $entrega->fecha_firma_esc = $fecha_firma_esc->formatLocalized('%d de %B de %Y');

                            $fecha_program = new Carbon($entrega->fecha_program);
                            $entrega->fecha_program = $fecha_program->formatLocalized('%d de %B de %Y');

                            $sheet->row($index+2, [
                                $entrega->folio, 
                                $entrega->proyecto,
                                $entrega->etapa,
                                $entrega->manzana,
                                $entrega->num_lote,
                                $entrega->nombre_cliente,
                                $entrega->celular,
                                "Paquete: $entrega->paquete | Promoción: $entrega->promocion",
                                $entrega->fecha_firma_esc,
                                "$entrega->fecha_program",
                                "$entrega->hora_entrega_prog"
                            ]);	
                        }
                        $num='A1:K'.$cont;
                        $sheet->setBorder($num, 'thin');
                    });
                }
            )->download('xls');
        }

        public function indexPendientes(Request $request){
            
            if(!$request->ajax())return redirect('/');
            $criterio = $request->criterio;
            $buscar = $request->buscar;
            $b_etapa = $request->b_etapa;
            $b_manzana = $request->b_manzana;
            $b_lote = $request->b_lote;

            $fecha = Carbon::now();
            $mytime = $fecha->toTimeString();
            $hoy =  $fecha->toDateString();

            $query = Entrega::join('contratos','entregas.id','contratos.id')
                ->leftJoin('expedientes', 'contratos.id','expedientes.id')
                ->join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id as folio', 
                    'contratos.equipamiento',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'c.celular', 
                    'c.f_nacimiento','c.rfc',
                    'c.homoclave','c.direccion','c.colonia','c.cp',
                    'c.telefono','c.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida', 'etapas.factibilidad',

                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.paquete',
                    'creditos.promocion',
                    'creditos.descripcion_paquete',
                    'creditos.descripcion_promocion',
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
                    'entregas.fecha_program',
                    'entregas.hora_entrega_prog',
                    'entregas.fecha_entrega_real',
                    'entregas.hora_entrega_real',
                    'entregas.revision_previa',
                    DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                );

            if($buscar == ''){
                $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0);
            }
            else{
                switch($criterio){
                    case 'c.nombre':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');

                        break;
                    }

                    case 'entregas.fecha_program':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->whereBetween($criterio, [$buscar, $b_etapa]);

                        break;
                    }

                    case 'contratos.id':{
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
                        ->where($criterio, '=', $buscar);

                        break;
                    }

                    case 'lotes.fraccionamiento_id':{
                        if($request->b_desde == ''){
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa);
        
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.num_lote', '=', $b_lote);
        
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                        }
                        else{
                            if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta]);
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa);
        
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                            }
                            elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.etapa_id', '=', $b_etapa)
                                ->where('lotes.num_lote', '=', $b_lote);
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.num_lote', '=', $b_lote)
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                            elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.num_lote', '=', $b_lote);
        
                            }
                            elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                                $contratos = $query
                                ->where('contratos.status', '!=', 0)
                                ->where('contratos.status', '!=', 2)
                                ->where('contratos.entregado', '=', 0)
                                ->where($criterio, '=', $buscar)
                                ->whereBetween('entregas.fecha_program', [$request->b_desde,$request->b_hasta])
                                ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
        
                            }
                        }
                        

                        break;
                    }
                }
            }

           $contratos = $contratos->orderBy('licencias.avance','desc')->orderBy('lotes.fecha_entrega_obra','desc')->paginate(8);

           if(sizeOf($contratos)){
               foreach($contratos as $index => $contrato){
                    $equipamiento = Solic_equipamiento::select('fecha_colocacion','fin_instalacion')
                            ->where('contrato_id','=',$contrato->folio)
                            ->orderBy('fecha_colocacion','desc')->get();
                    if(sizeof($equipamiento)){
                        $contrato->fecha_colocacion = $equipamiento[0]->fecha_colocacion;
                        $contrato->fin_instalacion = $equipamiento[0]->fin_instalacion;
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
    //{{{{{{{{{{{{{{{{{{{ SECCION PARA ENTREGA DE VIVIENDAS }}}}}}}}}}}}}}}}}}}

    public function indexObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_entrega::select('comentario','usuario','created_at')
                    ->where('entrega_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_entrega();
        $observacion->entrega_id = $request->entrega_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function setFechaObra(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $lote = Lote::findOrFail($request->lote_id);
            $lote->fecha_entrega_obra = $request->fecha_entrega_obra;
            $lote->save();

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->folio;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function setFechaProgramada(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $entrega = Entrega::findOrFail($request->folio);
        $entrega->fecha_program = $request->fecha_program;
        if($request->mot_program == 'Contratista'){
            $entrega->cont_reprogram += 1;
        }
        $entrega->save();    
        
        $credito = Credito::findOrFail($request->folio);
        
        if($request->observacion != ''){
            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->folio;
            $observacion->comentario = 'Programada: '.$request->observacion;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();
        }

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = 'Se ha progrmadado la entrega del lote '.$credito->num_lote.' proyecto: '.$credito->fraccionamiento.' etapa: '.$credito->etapa;
                $arregloAceptado = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Nueva entrega programada'
                    ]
                ];

                    User::findOrFail(25694)->notify(new NotifyAdmin($arregloAceptado));
                    User::findOrFail(26001)->notify(new NotifyAdmin($arregloAceptado));
                    User::findOrFail(25695)->notify(new NotifyAdmin($arregloAceptado));
                    User::findOrFail(26000)->notify(new NotifyAdmin($arregloAceptado));

    }

    public function setHoraProgramada(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $entrega = Entrega::findOrFail($request->folio);
        $entrega->hora_entrega_prog = $request->hora_entrega_prog;
        $entrega->save();       
    }

    public function finalizarEntrega(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();
            $entrega = Entrega::findOrFail($request->id);
            $entrega->fecha_entrega_real = $request->fecha_entrega_real;
            $entrega->hora_entrega_real = $request->hora_entrega_real;
            $entrega->cero_detalles = $request->cero_detalles;

            if($entrega->fecha_program == $entrega->fecha_entrega_real)
                $entrega->puntualidad = 1;

            $entrega->status = 1;
            $entrega->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->entregado = 1;
            $contrato->save();

            if($request->comentario != ''){
                $observacion = new Obs_entrega();
                $observacion->entrega_id = $request->id;
                $observacion->comentario = $request->comentario;
                $observacion->usuario = Auth::user()->usuario;
                $observacion->save();
            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function indexEntregas(Request $request){
        if(!$request->ajax())return redirect('/');
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $query = Entrega::join('contratos','entregas.id','contratos.id')
            ->join('expedientes','contratos.id','expedientes.id')
            ->join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            ->join('personal as c', 'clientes.id', '=', 'c.id')
            ->select('contratos.id as folio', 
                'contratos.equipamiento',
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                'c.celular', 
                'c.f_nacimiento','c.rfc',
                'c.homoclave','c.direccion','c.colonia','c.cp',
                'c.telefono','c.email','creditos.num_dep_economicos',
                'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida', 'etapas.factibilidad',

                'creditos.fraccionamiento as proyecto',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.num_lote',
                'creditos.paquete',
                'creditos.promocion',
                'creditos.descripcion_paquete',
                'creditos.descripcion_promocion',
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
                'entregas.fecha_program',
                'entregas.hora_entrega_prog',
                'entregas.fecha_entrega_real',
                'entregas.hora_entrega_real',
                'entregas.cont_reprogram',
                DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
            );

        if($buscar == ''){
            $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }

                case 'entregas.fecha_entrega_real':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->whereBetween($criterio, [$buscar, $b_etapa]);
                    break;
                }

                case 'contratos.id':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where($criterio, '=', $buscar);
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');

                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote);
                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote);

                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }

                    break;
                }
            }
        }

        $contratos = $contratos->orderBy('licencias.avance','desc')
                                ->orderBy('lotes.fecha_entrega_obra','desc')
                                ->paginate(8);

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ],'contratos' => $contratos,
        ];   
    }

    public function cartaCuotaMantenimiento($id){
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('licencias', 'lotes.id', '=', 'licencias.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'c.celular', 
                        'c.f_nacimiento','c.rfc',
                        'c.homoclave','c.direccion','c.colonia','c.cp',
                        'c.telefono','c.email','creditos.num_dep_economicos',
                        'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                        'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                        'clientes.nacionalidad','clientes.sexo',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'etapas.num_cuenta_admin',
                        'etapas.clabe_admin',
                        'etapas.sucursal_admin',
                        'etapas.titular_admin',
                        'etapas.banco_admin',
                        'etapas.costo_mantenimiento',
                        'fraccionamientos.email_administracion',
                        'fraccionamientos.logo_fracc',
                       
                        'lotes.id as loteId',
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    // ->where('contratos.entregado', '=', 1)
                    ->where('contratos.id','=',$id)
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->get();

            $contratos[0]->costo_mantenimiento_letra = NumerosEnLetras::convertir($contratos[0]->costo_mantenimiento, 'Pesos', true, 'Centavos');

            $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaServicios', ['contratos' => $contratos]);
            return $pdf->stream('carta_cuota_mantenimiento.pdf');
    }

    public function cartaRecepcion($id){
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id as folio', 
                        'contratos.equipamiento',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'fraccionamientos.logo_fracc',
                        'fraccionamientos.delegacion',
                       
                        'lotes.id as loteId',
                        'lotes.calle',
                        'lotes.numero'
                    )
                    // ->where('contratos.entregado', '=', 1)
                    ->where('contratos.id','=',$id)
                    ->get();

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $now= Carbon::now();
                    $contratos[0]->fecha_hoy = $now->formatLocalized('%d de %B de %Y');

            $contratos[0]->costo_mantenimiento_letra = NumerosEnLetras::convertir($contratos[0]->costo_mantenimiento, 'Pesos', true, 'Centavos');

            $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaRecepcion', ['contratos' => $contratos]);
            return $pdf->stream('carta_cuota_mantenimiento.pdf');
    }

    public function polizaDeGarantia(Request $request,$id){
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
        ->join('expedientes','contratos.id','expedientes.id')
        ->join('creditos', 'contratos.id', '=', 'creditos.id')
        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
        ->join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
        ->join('licencias', 'lotes.id', '=', 'licencias.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->select('contratos.id as folio', 
            'contratos.equipamiento',
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
            'c.celular', 
            'c.f_nacimiento','c.rfc',
            'c.homoclave','c.direccion','c.colonia','c.cp',
            'c.telefono','c.email','creditos.num_dep_economicos',
            'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
            'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
            'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
            'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
            'contratos.ext_empresa','contratos.colonia_empresa',

            'creditos.fraccionamiento as proyecto',
            'creditos.etapa',
            'creditos.manzana',
            'creditos.num_lote',
            'creditos.paquete',
            'creditos.promocion',
            'creditos.descripcion_paquete',
            'creditos.descripcion_promocion',
            'licencias.avance as avance_lote',
            'licencias.visita_avaluo',
            'contratos.fecha_status',
            'contratos.status',
            'contratos.equipamiento',
            'expedientes.fecha_firma_esc',
            'lotes.fecha_entrega_obra',
            'lotes.id as loteId',
            'lotes.calle',
            'lotes.numero',
            'fraccionamientos.ciudad as ciudadFraccionamiento',
            'fraccionamientos.delegacion',
            'entregas.fecha_program',
            'entregas.hora_entrega_prog',
            'entregas.fecha_entrega_real',
            'entregas.hora_entrega_real',
            'fraccionamientos.logo_fracc',
            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
        )
        ->where('contratos.status', '!=', 0)
        ->where('contratos.status', '!=', 2)
        //->where('contratos.entregado', '=', 1)
        ->where('contratos.id','=',$id)
        ->orderBy('licencias.avance','desc')
        ->orderBy('lotes.fecha_entrega_obra','desc')
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.PolizaDeGarantia', ['contratos' => $contratos, 'tiempo' => $request->tiempo]);
        return $pdf->stream('poliza_de_garantia.pdf');
    }

    public function cartaAlarma(Request $request){
        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
        ->join('creditos', 'contratos.id', '=', 'creditos.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->select('contratos.id as folio', 
            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente")
        )
        ->where('contratos.id','=',$request->id)
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.CartaAlarma', ['alarma' => $contratos]);
        return $pdf->stream('carta_alarma.pdf');
    }

    public function select_ultimaFecha_instalacion(Request $request){
        if(!$request->ajax())return redirect('/');
        $contrato = $request->id;

        $fecha_ultima = Entrega::join('contratos','entregas.id','contratos.id')
        ->leftjoin('solic_equipamientos','contratos.id','=','solic_equipamientos.contrato_id')
        ->join('expedientes','contratos.id','expedientes.id')
        ->join('creditos', 'contratos.id', '=', 'creditos.id')
        ->select('solic_equipamientos.fin_instalacion')
        ->orderBy('solic_equipamientos.fin_instalacion','DESC')
        ->where('solic_equipamientos.contrato_id','=',$contrato)->get();
        return ['fecha_ultima' => $fecha_ultima];
    }

    public function setDatosCuenta (Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $datoCuentas = Etapa::findOrFail($request->id);
        $datoCuentas->num_cuenta_admin = $request->cuenta;
        $datoCuentas->clabe_admin = $request->clabe;
        $datoCuentas->sucursal_admin = $request->sucursal;
        $datoCuentas->titular_admin = $request->titular;
        $datoCuentas->banco_admin = $request->banco;
        $datoCuentas->save();
    }

    public function actualizarCorreoAdmin(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $correo = Fraccionamiento::findOrFail($request->id);
        $correo->email_administracion = $request->correo;
        $correo->save();
    }

    public function getDatosLoteEntregado(Request $request){
        if(!$request->ajax() )return redirect('/');
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
                    ->where('lotes.id','=',$request->lote)->get();

        $datosContratista = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                    ->select('contratistas.id','contratistas.nombre')
                    ->where('ini_obras.clave','=',$datosLote[0]->aviso)->get();

        return ['datosLote' => $datosLote,
                'datosContratista' => $datosContratista
                ];
    }

    public function excelEntregas(Request $request){
        
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        $query = Entrega::join('contratos','entregas.id','contratos.id')
                ->leftJoin('expedientes','contratos.id','expedientes.id')
                ->join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id as folio', 
                    'contratos.equipamiento',
                    DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                    'c.celular', 
                    'c.f_nacimiento','c.rfc',
                    'c.homoclave','c.direccion','c.colonia','c.cp',
                    'c.telefono','c.email','creditos.num_dep_economicos',
                    'creditos.tipo_economia','clientes.email_institucional','clientes.edo_civil','clientes.nss',
                    'clientes.curp','clientes.empresa','clientes.estado','clientes.ciudad','clientes.puesto',
                    'clientes.nacionalidad','clientes.sexo','contratos.direccion_empresa',
                    'contratos.cp_empresa','contratos.estado_empresa','contratos.ciudad_empresa','contratos.telefono_empresa',
                    'contratos.ext_empresa','contratos.colonia_empresa','etapas.carta_bienvenida',

                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.paquete',
                    'creditos.promocion',
                    'creditos.descripcion_paquete',
                    'creditos.descripcion_promocion',
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
                    'entregas.fecha_program',
                    'entregas.hora_entrega_prog',
                    'entregas.fecha_entrega_real',
                    'entregas.hora_entrega_real',
                    'entregas.cont_reprogram',
                    DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
            );

        if($buscar == ''){
            $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }

                case 'entregas.fecha_entrega_real':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->whereBetween($criterio, [$buscar, $b_etapa]);
                    break;
                }

                case 'contratos.id':{
                    $contratos = $query
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where($criterio, '=', $buscar);
                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote);
                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote);
                    }
                    elseif($b_etapa == '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = $query
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.manzana', 'like', '%'. $b_manzana . '%');
                    }
                    break;
                }
            }
        }

        $contratos = $contratos->orderBy('licencias.avance','desc')
                                ->orderBy('lotes.fecha_entrega_obra','desc')
                                ->get();

        return Excel::create('Entregas', function($excel) use ($contratos){
            $excel->sheet('Entregas', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref','Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Cliente','# Celular', 'Fecha de firma', 'Fecha entrega (Obra)', 'Paquete y/o Promoción',
                    'Equipamiento','Fecha de entrega','# Reprogramaciones'
                ]);

                $sheet->cells('A1:M1', function ($cells) {
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

                foreach($contratos as $index => $entrega) {
                    $cont++;

                    $paquete = 'Paquete: '.$entrega->paquete.' Promoción: '.$entrega->promocion;

                    if($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';
                
                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';
                
                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 0)
                        $equipamiento='Equipamiento sin solicitarse';
                
                    elseif ($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';
                
                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';
                
                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 1)
                        $equipamiento='En proceso de instalación';
                
                    elseif ($entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';
                
                    elseif ($entrega->descripcion_paquete && !$entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';
                
                    elseif (!$entrega->descripcion_paquete && $entrega->descripcion_promocion && $entrega->equipamiento == 2)
                        $equipamiento='Equipamiento instalado';
                
                    elseif (!$entrega->descripcion_paquete && !$entrega->descripcion_promocion) $equipamiento='Sin equipamiento';
                    else $equipamiento='Sin equipamiento';

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($entrega->fecha_firma_esc);
                    $entrega->fecha_firma_esc = $fecha1->formatLocalized('%d de %B de %Y');
                    $fecha2 = new Carbon($entrega->fecha_entrega_obra);
                    $entrega->fecha_entrega_obra = $fecha2->formatLocalized('%d de %B de %Y');
                    $fecha3 = new Carbon($entrega->fecha_entrega_real);
                    $entrega->fecha_entrega_real = $fecha3->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $entrega->folio, 
                        $entrega->proyecto,
                        $entrega->etapa,
                        $entrega->manzana,
                        $entrega->num_lote,
                        $entrega->nombre_cliente,
                        $entrega->celular,
                        $entrega->fecha_firma_esc,
                        $entrega->fecha_entrega_obra,
                        $paquete,
                        $equipamiento,
                        $entrega->fecha_entrega_real,
                        $entrega->cont_reprogram

                    ]);	
                }
                $num='A1:J' . $cont;
                $sheet->setBorder($num, 'thin');
            });
            }
        )->download('xls');
    }

}
