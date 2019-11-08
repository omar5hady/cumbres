<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrega;
use App\Obs_entrega;
use DB;
use Auth;
use App\Expediente;
use App\lote;
use Carbon\Carbon;
use App\Contrato;
use App\Etapa;
use App\Fraccionamiento;
use NumerosEnLetras;

class EntregaController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        try{
            DB::beginTransaction();
            $entrega = new Entrega();
            $entrega->id = $request->id;
            $entrega->save();

            $expediente = Expediente::findOrFail($request->id);
            $expediente->postventa = 1;
            $expediente->save();

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->id;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function indexPendientes(Request $request){
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }

                case 'entregas.fecha_program':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->whereBetween($criterio, [$buscar, $b_etapa])
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }

                case 'contratos.id':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 0)
                    ->where($criterio, '=', $buscar)
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 0)
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
        $entrega->save();       
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
        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;

        if($buscar == ''){
            $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'c.nombre':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar . '%')
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }

                case 'entregas.fecha_entrega_real':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->whereBetween($criterio, [$buscar, $b_etapa])
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }

                case 'contratos.id':{
                    $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                    ->join('expedientes','contratos.id','expedientes.id')
                    ->join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                        'entregas.fecha_program',
                        'entregas.hora_entrega_prog',
                        'entregas.fecha_entrega_real',
                        'entregas.hora_entrega_real',
                        DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                    )
                    ->where('contratos.status', '!=', 0)
                    ->where('contratos.status', '!=', 2)
                    ->where('contratos.entregado', '=', 1)
                    ->where($criterio, '=', $buscar)
                    ->orderBy('licencias.avance','desc')
                    ->orderBy('lotes.fecha_entrega_obra','desc')
                    ->paginate(8);

                    break;
                }
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Entrega::join('contratos','entregas.id','contratos.id')
                        ->join('expedientes','contratos.id','expedientes.id')
                        ->join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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
                            'entregas.fecha_program',
                            'entregas.hora_entrega_prog',
                            'entregas.fecha_entrega_real',
                            'entregas.hora_entrega_real',
                            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
                        )
                        ->where('contratos.status', '!=', 0)
                        ->where('contratos.status', '!=', 2)
                        ->where('contratos.entregado', '=', 1)
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

    public function polizaDeGarantia($id){
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
            'entregas.fecha_program',
            'entregas.hora_entrega_prog',
            'entregas.fecha_entrega_real',
            'entregas.hora_entrega_real',
            DB::raw('DATEDIFF(lotes.fecha_entrega_obra,expedientes.fecha_firma_esc) as diferencia_obra')
        )
        ->where('contratos.status', '!=', 0)
        ->where('contratos.status', '!=', 2)
        ->where('contratos.entregado', '=', 1)
        ->where('contratos.id','=',$id)
        ->orderBy('licencias.avance','desc')
        ->orderBy('lotes.fecha_entrega_obra','desc')
        ->get();

        $pdf = \PDF::loadview('pdf.DocsPostVenta.PolizaDeGarantia', ['contratos' => $contratos]);
        return $pdf->stream('poliza_de_garantia.pdf');
    }

    public function select_ultimaFecha_instalacion(Request $request){
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
        $datoCuentas = Etapa::findOrFail($request->id);
        $datoCuentas->num_cuenta_admin = $request->cuenta;
        $datoCuentas->clabe_admin = $request->clabe;
        $datoCuentas->sucursal_admin = $request->sucursal;
        $datoCuentas->titular_admin = $request->titular;
        $datoCuentas->banco_admin = $request->banco;
        $datoCuentas->save();
    }

    public function actualizarCorreoAdmin(Request $request){
        $correo = Fraccionamiento::findOrFail($request->id);
        $correo->email_administracion = $request->correo;
        $correo->save();
    }

}
