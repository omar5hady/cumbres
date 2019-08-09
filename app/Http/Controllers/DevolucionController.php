<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Credito;
use App\Contrato;
use App\Devolucion;
//use App\Pago_contrato;
use DB;
//use Excel;

class DevolucionController extends Controller
{
    public function indexCancelaciones(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
       
        if ($buscar == '') {
            $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                ->select(
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.precio_venta',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',

                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    

                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.total_pagar',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion',

                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '=', '0')
                ->orderBy('id', 'desc')->paginate(8);

            // $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            //     ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            //     ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            //     ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            //     ->select('contratos.id as contratoId')
            //     ->where('contratos.status', '=', '0')
            //     ->where('contratos.devolucion', '=', '0')
            //     ->orderBy('id', 'desc')->count();
        }
        else{
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == '')
                    {
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                    ->select(
                        'creditos.id',
                        'creditos.prospecto_id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.precio_base',
                        'creditos.precio_venta',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.lote_id',

                        'personal.nombre',
                        'personal.apellidos',
                        'personal.telefono',
                        'personal.celular',
                        'personal.email',
                        'personal.direccion',
                        'personal.cp',
                        'personal.colonia',
                        'personal.f_nacimiento',
                        'personal.rfc',
                        'personal.homoclave',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                        
                        'v.nombre as vendedor_nombre',
                        'v.apellidos as vendedor_apellidos',
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        

                        'contratos.status',
                        'contratos.fecha_status',
                        'contratos.total_pagar',
                        'contratos.monto_total_credito',
                        'contratos.enganche_total',
                        'contratos.avance_lote',
                        'contratos.observacion',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '=', '0')
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $contratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '0')
                        ->orderBy('id', 'desc')->paginate(8);
                    
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
            ], 'contratos' => $contratos//, 'contadorContrato' => $contadorContratos
        ];
    }

    public function storeDevolucion(Request $request){
        try{
            DB::beginTransaction();
        
            $devolucion = new Devolucion();
            $devolucion->id = $request->id;
            $devolucion->concepto = $request->concepto;
            $devolucion->monto_cargo = $request->monto_cargo;
            $devolucion->devolver = $request->devolver;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->devolucion = 1;
            $contrato->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }

    public function indexDevoluciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
       
        if ($buscar == '') {
            $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('devoluciones','contratos.id','=','devoluciones.id')
                ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                ->select(
                    'creditos.id',
                    'creditos.prospecto_id',
                    'creditos.etapa',
                    'creditos.manzana',
                    'creditos.num_lote',
                    'creditos.modelo',
                    'creditos.precio_base',
                    'creditos.precio_venta',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.lote_id',

                    'personal.nombre',
                    'personal.apellidos',
                    'personal.telefono',
                    'personal.celular',
                    'personal.email',
                    'personal.direccion',
                    'personal.cp',
                    'personal.colonia',
                    'personal.f_nacimiento',
                    'personal.rfc',
                    'personal.homoclave',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                    
                    'v.nombre as vendedor_nombre',
                    'v.apellidos as vendedor_apellidos',
                    DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                    

                    'contratos.status',
                    'contratos.fecha_status',
                    'contratos.total_pagar',
                    'contratos.monto_total_credito',
                    'contratos.enganche_total',
                    'contratos.avance_lote',
                    'contratos.observacion',

                    'devoluciones.fecha',
                    'devoluciones.concepto',
                    'devoluciones.monto_cargo',
                    'devoluciones.cheque',
                    'devoluciones.cuenta',
                    'devoluciones.observaciones',


                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '=', '1')
                ->orderBy('id', 'desc')->paginate(8);

            // $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            //     ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            //     ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            //     ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            //     ->select('contratos.id as contratoId')
            //     ->where('contratos.status', '=', '0')
            //     ->where('contratos.devolucion', '=', '0')
            //     ->orderBy('id', 'desc')->count();
        }
        else{
            switch ($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa == '' && $b_manzana == '' && $b_lote == '')
                    {
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',
                            
                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('devoluciones','contratos.id','=','devoluciones.id')
                    ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                    ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                    ->select(
                        'creditos.id',
                        'creditos.prospecto_id',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.modelo',
                        'creditos.precio_base',
                        'creditos.precio_venta',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.lote_id',

                        'personal.nombre',
                        'personal.apellidos',
                        'personal.telefono',
                        'personal.celular',
                        'personal.email',
                        'personal.direccion',
                        'personal.cp',
                        'personal.colonia',
                        'personal.f_nacimiento',
                        'personal.rfc',
                        'personal.homoclave',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                        
                        'v.nombre as vendedor_nombre',
                        'v.apellidos as vendedor_apellidos',
                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                        

                        'contratos.status',
                        'contratos.fecha_status',
                        'contratos.total_pagar',
                        'contratos.monto_total_credito',
                        'contratos.enganche_total',
                        'contratos.avance_lote',
                        'contratos.observacion',

                        'devoluciones.fecha',
                        'devoluciones.concepto',
                        'devoluciones.monto_cargo',
                        'devoluciones.cheque',
                        'devoluciones.cuenta',
                        'devoluciones.observaciones',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '=', '1')
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.id')
                        ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
                        ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
                        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                        ->join('personal as v', 'clientes.vendedor_id', 'v.id')
                        ->select(
                            'creditos.id',
                            'creditos.prospecto_id',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.modelo',
                            'creditos.precio_base',
                            'creditos.precio_venta',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.lote_id',

                            'personal.nombre',
                            'personal.apellidos',
                            'personal.telefono',
                            'personal.celular',
                            'personal.email',
                            'personal.direccion',
                            'personal.cp',
                            'personal.colonia',
                            'personal.f_nacimiento',
                            'personal.rfc',
                            'personal.homoclave',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),
                            
                            'v.nombre as vendedor_nombre',
                            'v.apellidos as vendedor_apellidos',
                            DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                            

                            'contratos.status',
                            'contratos.fecha_status',
                            'contratos.total_pagar',
                            'contratos.monto_total_credito',
                            'contratos.enganche_total',
                            'contratos.avance_lote',
                            'contratos.observacion',

                            'devoluciones.fecha',
                            'devoluciones.concepto',
                            'devoluciones.monto_cargo',
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '1')
                        ->orderBy('id', 'desc')->paginate(8);
                    
                    break;
                }
            }
        }
        
        return [
            'pagination' => [
                'total'         => $devoluciones->total(),
                'current_page'  => $devoluciones->currentPage(),
                'per_page'      => $devoluciones->perPage(),
                'last_page'     => $devoluciones->lastPage(),
                'from'          => $devoluciones->firstItem(),
                'to'            => $devoluciones->lastItem(),
            ], 'devoluciones' => $devoluciones//, 'contadorContrato' => $contadorContratos
        ];
    }
}
