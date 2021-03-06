<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use File;
use App\Notifications\NotifyAdmin;
use Carbon\Carbon;
use App\Documentos_pago;
use App\Solicitudes_pago;
use App\Obs_orden_pago;

class SolicitudPagosController extends Controller
{
    public function indexSolicitudes(Request $request){

        $buscar = $request->buscar;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $fecha2 = $fecha2.' 23:59:59';

        $query = Solicitudes_pago::join('users','solicitudes_pagos.user_id','=','users.id')
                        ->join('personal','users.id','=','personal.id')
                        ->select('personal.nombre','personal.apellidos','solicitudes_pagos.id',
                                'solicitudes_pagos.concepto','solicitudes_pagos.orden_compra',
                                'solicitudes_pagos.status','solicitudes_pagos.doc_orden',
                                'solicitudes_pagos.autorizacion_orden', 'solicitudes_pagos.cotizacion',
                                'solicitudes_pagos.pago_partes','solicitudes_pagos.created_at',
                                'solicitudes_pagos.factura',
                                'solicitudes_pagos.check1',
                                'solicitudes_pagos.fecha_status',
                                'solicitudes_pagos.check2',
                                'solicitudes_pagos.check3',
                                'solicitudes_pagos.orden_vistoBueno',
                                'solicitudes_pagos.solic_cheque','solicitudes_pagos.user_id');


        if($request->tipo == 0){
            $solicitudes = $query->where('solicitudes_pagos.user_id','=',Auth::user()->id);
        }
        else{
            $solicitudes = $query;
        }

        if($buscar != ''){
            $solicitudes = $solicitudes->where('solicitudes_pagos.id','=',$buscar);
        }

        if($fecha1 != ''){
            $solicitudes = $solicitudes->whereBetween('solicitudes_pagos.created_at', [$fecha1, $fecha2]);
        }

        $solicitudes = $solicitudes->orderBy('solicitudes_pagos.created_at','desc')->paginate(10);

        return [
            'pagination' => [
                'total'         => $solicitudes->total(),
                'current_page'  => $solicitudes->currentPage(),
                'per_page'      => $solicitudes->perPage(),
                'last_page'     => $solicitudes->lastPage(),
                'from'          => $solicitudes->firstItem(),
                'to'            => $solicitudes->lastItem(),
            ],
            'solicitudes'=>$solicitudes
        ];
    }

    public function storeSinOrden(Request $request,$concepto){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/solicCheque'), $fileName);

        if($moved){
            $solicitud = new Solicitudes_pago();
            $solicitud->solic_cheque = $fileName;
            $solicitud->concepto = $concepto;
            $solicitud->orden_compra = 0;
            $solicitud->user_id = Auth::user()->id;
            $solicitud->save(); //Insert
    
        }
        
        return response()->json(['success'=>'Solicitud de cheque cargada correctamente.']);
       
    }

    public function getDocumentos(Request $request){
        if(!$request->ajax())return redirect('/');

        $versiones = Documentos_pago::select('archivo','nombre','id')->where('solicitud_id','=',$request->id)->get();

        return['versiones'=>$versiones];
    }

    public function deleteArchivo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $archivo = Documentos_pago::findOrFail($request->id);
        $image_path =  public_path().'/files/solicPago/documentos/'.$archivo->archivo;
        unlink($image_path);
        $archivo->delete();

    }

    public function storeDocumento(Request $request,$id,$nombre){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/documentos'), $fileName);

        if($moved){
            $documento = new Documentos_pago();
            $documento->solicitud_id = $id;
            $documento->nombre = $nombre;
            $documento->archivo = $fileName;
            $documento->usuario = Auth::user()->usuario;
            $documento->save(); //Insert
    
        }
        
        return response()->json(['success'=>'Documento cargado correctamente.']);
       
    }

    public function storeConOrden(Request $request,$concepto){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/ordenCompra'), $fileName);

        if($moved){
            $solicitud = new Solicitudes_pago();
            $solicitud->doc_orden = $fileName;
            $solicitud->concepto = $concepto;
            $solicitud->orden_compra = 1;
            $solicitud->user_id = Auth::user()->id;
            $solicitud->save(); //Insert
    
        }
        
        return response()->json(['success'=>'Solicitud de cheque cargada correctamente.']);
       
    }

    public function putSolicCheque(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/solicCheque'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->solic_cheque != null){
                $image_path =  public_path().'/files/solicPago/solicCheque/'.$solicitud->solic_cheque;
                unlink($image_path);    
            }
            $solicitud->solic_cheque = $fileName;
            $solicitud->status = 1;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Solicitud de cheque cargada correctamente.']);
       
    }

    public function putCotizacion(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/cotizacion'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->cotizacion != null){
                $image_path =  public_path().'/files/solicPago/cotizacion/'.$solicitud->cotizacion;
                unlink($image_path);    
            }
            $solicitud->cotizacion = $fileName;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Cotizacion cargada correctamente.']);
       
    }

    public function putPagoPartes(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/pagoPartes'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->pago_partes != null){
                $image_path =  public_path().'/files/solicPago/pagoPartes/'.$solicitud->pago_partes;
                unlink($image_path);    
            }
            $solicitud->pago_partes = $fileName;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Cotizacion cargada correctamente.']);
       
    }

    public function putFactura(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/factura'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->factura != null){
                $image_path =  public_path().'/files/solicPago/factura/'.$solicitud->factura;
                unlink($image_path);    
            }
            $solicitud->factura = $fileName;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Factura cargada correctamente.']);
       
    }

    public function putOrden(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/ordenCompra'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->doc_orden != null){
                $image_path =  public_path().'/files/solicPago/ordenCompra/'.$solicitud->doc_orden;
                unlink($image_path);    
            }
            $solicitud->doc_orden = $fileName;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Orden de compra cargada correctamente.']);
       
    }

    public function putCheque(Request $request,$id){
        if(!$request->ajax())return redirect('/');

        $fileName = uniqid().'.'.$request->archivo->getClientOriginalExtension();
        $moved =  $request->archivo->move(public_path('/files/solicPago/solicCheque'), $fileName);

        if($moved){
            $solicitud = Solicitudes_pago::findOrFail($id);
            if($solicitud->solic_cheque != null){
                $image_path =  public_path().'/files/solicPago/solicCheque/'.$solicitud->solic_cheque;
                unlink($image_path);    
            }
            $solicitud->solic_cheque = $fileName;
            $solicitud->save(); //Insert
        }
        
        return response()->json(['success'=>'Solicitud de cheque cargado correctamente.']);
       
    }

    public function indexComentarios(Request $request){
        if(!$request->ajax())return redirect('/');
        $id = $request->id;
        $observacion = Obs_orden_pago::select('observacion','usuario','created_at','id')
                    ->where('solicitud_id','=', $id)->orderBy('created_at','desc')->get();

        return [
            'observacion' => $observacion
        ];
    }

    public function storeComentarios(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function autorizarOrden(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->autorizacion_orden = $fecha;
        $solicitud->status = 1;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'Orden de compra autorizada.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "La orden de compra #" . $request->id . ' ha sido autorizada';
                $arreglo = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Orden de compra autorizada'
                    ]
                ];


                User::findOrFail($solicitud->user_id)->notify(new NotifyAdmin($arreglo));
    }

    public function autorizarSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->check2 = $fecha;
        $solicitud->status = 3;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'Solicitud de cheque autorizado.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "La solicitud de cheque #" . $request->id . ' ha sido autorizado';
                $arreglo = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Solicitud de Cheque autorizado'
                    ]
                ];


                User::findOrFail($solicitud->user_id)->notify(new NotifyAdmin($arreglo));
    }

    public function vistoBuenoSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->check1 = $fecha;
        $solicitud->status = 2;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'La solicitud de cheque ha pasado a proceso de autorizacion.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function vistoBuenoOrden(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->orden_vistoBueno = $fecha;
        $solicitud->status = 0;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'La orden de compra ha pasado a proceso de autorizacion.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function pagarSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->check3 = $fecha;
        $solicitud->status = 4;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'La solicitud ha sido pagada';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function cancelarSolicitud(Request $request){
        if(!$request->ajax())return redirect('/');
        $fecha = Carbon::now();

        $solicitud = Solicitudes_pago::findOrFail($request->id);
        $solicitud->status = 5;
        $solicitud->fecha_status = $fecha;
        $solicitud->save(); //Insert

        $observacion = new Obs_orden_pago();
        $observacion->solicitud_id = $request->id;
        $observacion->observacion = 'La solicitud ha sido cancelada. Motivo: '.$request->motivo;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();

        $imagenUsuario = DB::table('users')->select('foto_user', 'usuario')->where('id', '=', Auth::user()->id)->get();
                $fecha = Carbon::now();
                $msj = "La solicitud #" . $request->id . ' ha sido cancelado';
                $arreglo = [
                    'notificacion' => [
                        'usuario' => $imagenUsuario[0]->usuario,
                        'foto' => $imagenUsuario[0]->foto_user,
                        'fecha' => $fecha,
                        'msj' => $msj,
                        'titulo' => 'Solicitud Cancelada'
                    ]
                ];


                User::findOrFail($solicitud->user_id)->notify(new NotifyAdmin($arreglo));
    }


}
