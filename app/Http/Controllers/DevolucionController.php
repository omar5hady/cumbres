<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use App\Devolucion;
use App\Gasto_admin;
use DB;
use App\Credito;
use Excel;
use Carbon\Carbon;
use Auth;

class DevolucionController extends Controller
{
    public function indexCancelaciones(Request $request)
    {
       // if(!$request->ajax())return redirect('/');
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

                    DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),
                    
                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                        WHERE gastos_admin.contrato_id = contratos.id
                        GROUP BY gastos_admin.contrato_id) as sumGastos"),

                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '!=', '2')
                ->orderBy('id', 'desc')->paginate(8);

            // $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            //     ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            //     ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            //     ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            //     ->select('contratos.id as contratoId')
            //     ->where('contratos.status', '=', '0')
            //     ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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

                        DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
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
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
        
            $contDev = Devolucion::select('id')->where('contrato_id','=',$request->id)->count();

            $devolucion = new Devolucion();
            $devolucion->contrato_id = $request->id;
            $devolucion->devolver = $request->cant_dev;
            $devolucion->fecha = $request->fecha;
            $devolucion->cheque = $request->cheque;
            $devolucion->cuenta = $request->cuenta;
            $devolucion->observaciones = $request->observaciones;
            $devolucion->save();

            $credito = Credito::findOrFail($request->id);
            $contrato = Contrato::findOrFail($request->id);
            $contrato->devolucion = 1;
            if($request->devolver == $request->cant_dev)
                $contrato->devolucion = 2;
            if($contDev==0){
                $contrato->saldo =  $contrato->saldo - $credito->precio_venta;    
            }
            $contrato->saldo += $request->cant_dev;
            $contrato->save();

            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->id;
            $gastos->concepto = "Devolución por cancelación";
            $gastos->costo = $request->cant_dev;
            $gastos->observacion = $request->observaciones;
            $gastos->fecha = $request->fecha;
            $gastos->save();

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
                ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                    'devoluciones.cheque',
                    'devoluciones.cuenta',
                    'devoluciones.observaciones',
                    'devoluciones.devolver',


                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '=', '2')
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
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',
                            

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                        
                        'devoluciones.cheque',
                        'devoluciones.cuenta',
                        'devoluciones.observaciones',
                        'devoluciones.devolver',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '=', '2')
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
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

    public function excelDevoluciones(Request $request){
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

                    DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),
                    
                    DB::raw("(SELECT SUM(gastos_admin.costo) FROM gastos_admin
                        WHERE gastos_admin.contrato_id = contratos.id
                        GROUP BY gastos_admin.contrato_id) as sumGastos"),

                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '!=', '2')
                ->orderBy('id', 'desc')->get();

            // $contadorContratos = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            //     ->join('personal', 'creditos.prospecto_id', '=', 'personal.id')
            //     ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
            //     ->join('personal as v', 'clientes.vendedor_id', 'v.id')
            //     ->select('contratos.id as contratoId')
            //     ->where('contratos.status', '=', '0')
            //     ->where('contratos.devolucion', '!=', '2')
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->get();
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

                        DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '!=', '2')
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->get();
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

                            DB::raw("(SELECT SUM(devoluciones.devolver) FROM devoluciones
                                WHERE devoluciones.contrato_id = contratos.id
                                GROUP BY devoluciones.contrato_id) as sumaDev"),

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '!=', '2')
                        ->orderBy('id', 'desc')->get();
                    
                    break;
                }
            }
        }
        
        return Excel::create('Devoluciones pendientes por cancelación', function($excel) use ($contratos){
            $excel->sheet('cancelaciones', function($sheet) use ($contratos){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Depositos', 'Pendiente a devolver', 'Fecha cancelación'
                ]);

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                    'H' => '$#,##0.00',
                ));


                $sheet->cells('A1:I1', function ($cells) {
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

                foreach($contratos as $index => $devolucion) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($devolucion->fecha_status);
                    $devolucion->fecha_status = $fecha1->formatLocalized('%d de %B de %Y');

                    $depositos = $devolucion->sumaPagares - $devolucion->sumaRestante;
                    $pendiente = $devolucion->sumaPagares - $devolucion->sumaRestante -  $devolucion->sumGastos;
                    if(($devolucion->sumaPagares - $devolucion->sumaRestante) > 0){
                        $sheet->row($index+2, [
                            $devolucion->id, 
                            $devolucion->nombre_cliente,
                            $devolucion->proyecto,
                            $devolucion->etapa,
                            $devolucion->manzana,
                            $devolucion->num_lote,
                            $depositos,
                            $pendiente,
                            $devolucion->fecha_status

                        ]);	
                    }
                }
                $num='A1:I' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
    )->download('xls');

        // return [
        //     'pagination' => [
        //         'total'         => $devoluciones->total(),
        //         'current_page'  => $devoluciones->currentPage(),
        //         'per_page'      => $devoluciones->perPage(),
        //         'last_page'     => $devoluciones->lastPage(),
        //         'from'          => $devoluciones->firstItem(),
        //         'to'            => $devoluciones->lastItem(),
        //     ], 'devoluciones' => $devoluciones//, 'contadorContrato' => $contadorContratos
        // ];
    }

    public function excelHistDev(Request $request){
       // if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_manzana = $request->b_manzana;
        $b_lote = $request->b_lote;
        $criterio = $request->criterio;
       
        if ($buscar == '') {
            $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                    'devoluciones.cheque',
                    'devoluciones.cuenta',
                    'devoluciones.observaciones',
                    'devoluciones.devolver',


                    DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                    
                    DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                WHERE pagos_contratos.contrato_id = contratos.id
                                GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                )
                ->where('contratos.status', '=', '0')
                ->where('contratos.devolucion', '=', '2')
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
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',
                            

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote == ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana != '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.manzana', '=', $b_manzana)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa != '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.etapa_id', '=', $b_etapa)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    elseif($b_etapa == '' && $b_manzana == '' && $b_lote != ''){
                        $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->where($criterio, '=', $buscar)
                        ->where('lotes.num_lote', '=', $b_lote)
                        ->orderBy('id', 'desc')->paginate(8);
                    }
                    
                    break;
                }
                case 'creditos.id':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                    ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                        
                        'devoluciones.cheque',
                        'devoluciones.cuenta',
                        'devoluciones.observaciones',
                        'devoluciones.devolver',

                        DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                        
                        DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                    WHERE pagos_contratos.contrato_id = contratos.id
                                    GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                    )
                    ->where('contratos.status', '=', '0')
                    ->where('contratos.devolucion', '=', '2')
                    ->where($criterio, '=', $buscar)
                    ->orderBy('id', 'desc')->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $devoluciones = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                        ->join('devoluciones','contratos.id','=','devoluciones.contrato_id')
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
                            
                            'devoluciones.cheque',
                            'devoluciones.cuenta',
                            'devoluciones.observaciones',
                            'devoluciones.devolver',

                            DB::raw("(SELECT SUM(pagos_contratos.monto_pago) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaPagares"),
                            
                            DB::raw("(SELECT SUM(pagos_contratos.restante) FROM pagos_contratos
                                        WHERE pagos_contratos.contrato_id = contratos.id
                                        GROUP BY pagos_contratos.contrato_id) as sumaRestante")
                        )
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                        ->where('contratos.status', '=', '0')
                        ->where('contratos.devolucion', '=', '2')
                        ->orderBy('id', 'desc')->paginate(8);
                    
                    break;
                }
            }
        }
        
        return Excel::create('devoluciones', function($excel) use ($devoluciones){
            $excel->sheet('devoluciones', function($sheet) use ($devoluciones){
                
                $sheet->row(1, [
                    '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                    '# Lote','Devuelto', 'Fecha cancelación', 'Fecha devolución',
                    '# Cheque', 'Cuenta'
                ]);

                $sheet->setColumnFormat(array(
                    'G' => '$#,##0.00',
                ));


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

                foreach($devoluciones as $index => $devolucion) {
                    $cont++;

                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($devolucion->fecha);
                    $fecha2 = new Carbon($devolucion->fecha_status);
                    $devolucion->fecha = $fecha1->formatLocalized('%d de %B de %Y');
                    $devolucion->fecha_status = $fecha2->formatLocalized('%d de %B de %Y');

                    $sheet->row($index+2, [
                        $devolucion->id, 
                        $devolucion->nombre. ' ' . $devolucion->apellidos,
                        $devolucion->proyecto,
                        $devolucion->etapa,
                        $devolucion->manzana,
                        $devolucion->num_lote,
                        $devolucion->devolver,
                        $devolucion->fecha_status,
                        $devolucion->fecha,
                        $devolucion->cheque,
                        $devolucion->cuenta,

                    ]);	
                }
                $num='A1:K' . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }
}
