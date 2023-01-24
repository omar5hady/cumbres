<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contrato;
use App\Credito;
use App\Bono_venta;
use App\Obs_bono_venta;
use DB;
use Auth;
use Carbon\Carbon;

// Controlador para bonos por venta para asesores.
/*  Por cada venta se le da un bono al asesor independiente a la comision generada.*/

class BonoVentaController extends Controller
{
    //Funcion que retorna los contratos que aun no tienen un bono de venta generado.
    public function indexContratos(Request $request){
        //if(!$request->ajax())return redirect('/');
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();
        $asesor = $request->b_asesor;

        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                            ->join('users', 'vendedores.id', '=', 'users.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('pagos_contratos as pc','contratos.id','=','pc.contrato_id')
                            ->join('personal as v', 'vendedores.id', '=', 'v.id')
                            ->select(
                                        'contratos.id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS asesor"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote',
                                        'creditos.precio_venta',
                                        'pc.num_pago','pc.fecha_pago','pc.pagado',
                                        'vendedores.tipo'
                            );

            /*
                Los campos a considerar son:
                    Primer pagare (apartado) abonado o pagado,
                    el vendedor es interno
                    el expediente debe estar entregado y el contrato firmado.
            */
            $contratos = $contratos->where('pc.num_pago','=',0)
                        ->where('pc.pagado','>=',1)
                        ->where('vendedores.tipo','=',0)
                        ->where('pc.tipo_pagare','=',0)
                        ->where('contratos.exp_bono','=',1)
                        ->where('contratos.status','=',3)
                        ->where('creditos.bono','=',0)
                        ->where('users.condicion','=',1)
                        ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $request->cliente . '%');
                        if($request->b_proyecto != '')
                            $contratos = $contratos->where('lotes.fraccionamiento_id','=',$request->b_proyecto);
                        if($request->b_etapa != '')
                            $contratos = $contratos->where('lotes.etapa_id','=',$request->b_etapa);
                        if($asesor != '')
                            $contratos = $contratos->where('creditos.vendedor_id','=',$asesor);
                        $contratos = $contratos->orWhere('pc.num_pago','=',0)
                        ->where('pc.pagado','=',3)
                        ->where('pc.tipo_pagare','=',0)
                        ->where('contratos.exp_bono','=',1)
                        ->where('vendedores.tipo','=',0)
                        ->where('users.condicion','=',1)
                        ->where('creditos.bono','=',0)
                        ->where('contratos.status','=',3)
                        ->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $request->cliente . '%');
                        if($request->b_proyecto != '')
                            $contratos = $contratos->where('lotes.fraccionamiento_id','=',$request->b_proyecto);
                        if($request->b_etapa != '')
                            $contratos = $contratos->where('lotes.etapa_id','=',$request->b_etapa);
                        if($asesor != '')
                            $contratos = $contratos->where('creditos.vendedor_id','=',$asesor);


            $contratos = $contratos
                            ->orderBy('contratos.id','desc')
                            ->paginate(8);

        return [
            'pagination' => [
                'total'         => $contratos->total(),
                'current_page'  => $contratos->currentPage(),
                'per_page'      => $contratos->perPage(),
                'last_page'     => $contratos->lastPage(),
                'from'          => $contratos->firstItem(),
                'to'            => $contratos->lastItem(),
            ],
            'contratos'=>$contratos, 'hoy'=>$hoy ];
    }

    // Función para crear el registro del bono
    public function storeBono(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $bono = new Bono_venta();
        $bono->contrato_id = $request->contrato_id;
        $bono->fecha_pago = $request->fecha_pago;
        $bono->monto = 1000;
        $bono->descripcion = $request->descripcion;
        $bono_num_bono = $request->num_bono;
        $bono->save();

        // Se busca el registro del credito y se habilita el campo bono en 1 para indicar que ya fue creado.
        $credito = Credito::findOrFail($request->contrato_id);
        $credito->bono = 1;
        $credito->save();

    }

    // Función para retornar los bonos creados.
    public function indexBonos(Request $request){

        $bonos = Bono_venta::join('contratos','bonos_ventas.contrato_id','=','contratos.id')
                                ->join('creditos','contratos.id','=','creditos.id')
                                ->join('lotes','creditos.lote_id','=','lotes.id')
                                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                                ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
                                ->join('personal as c', 'clientes.id', '=', 'c.id')
                                ->join('personal as v', 'vendedores.id', '=', 'v.id')
                                ->select('contratos.id as folio',
                                        DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS asesor"),
                                        'bonos_ventas.id',
                                        'bonos_ventas.fecha_pago',
                                        'bonos_ventas.monto',
                                        'bonos_ventas.descripcion',
                                        'bonos_ventas.status',
                                        'bonos_ventas.num_bono',
                                        'creditos.fraccionamiento',
                                        'creditos.etapa',
                                        'creditos.num_lote',
                                        'creditos.manzana',
                                        'creditos.vendedor_id',
                                        'contratos.fecha',
                                        DB::raw('MONTH(contratos.fecha) month'),
                                        DB::raw('YEAR(contratos.fecha) year')

                                );

        if($request->b_proyecto != ''){
            $bonos = $bonos->where('lotes.fraccionamiento_id','=',$request->b_proyecto);
        }

        if($request->b_etapa != ''){
            $bonos = $bonos->where('lotes.etapa_id','=',$request->b_etapa);
        }

        if($request->b_manzana != ''){
            $bonos = $bonos->where('lotes.manzana','like', '%'.$request->b_manzana.'%');
        }

        if($request->b_lote != ''){
            $bonos = $bonos->where('lotes.num_lote','=',$request->b_lote);
        }

        if($request->desde != '' && $request->hasta != ''){
            $bonos = $bonos->whereBetween('bonos_ventas.fecha_pago', [$request->desde, $request->hasta]);
        }

        if($request->b_asesor_id != ''){
            $bonos = $bonos->where('creditos.vendedor_id','=',$request->b_asesor_id);
        }

        $bonos = $bonos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $request->cliente . '%')
                            ->orderBy('bonos_ventas.fecha_pago','desc')->paginate(8);

        //Se verifica que el bono se pueda duplicar
        if(sizeOf($bonos)){
            foreach($bonos as $index => $bono){
                $fechaIni = $bono->year.'-'.$bono->month.'-01';
                $fechaFin = $bono->year.'-'.$bono->month.'-15';

                if($bono->month == '01'){
                    $mesAnt = 12;
                    $anioAnt = $bono->year - 1;
                }
                else{
                    $mesAnt = $bono->month - 1;
                    $anioAnt = $bono->year;
                }

                $bono->numVentas = Contrato::join('creditos','contratos.id','=','creditos.id')
                            ->select('contratos.id')
                            ->whereMonth('contratos.fecha',$bono->month)
                            ->whereYear('contratos.fecha',$bono->year)
                            ->where('creditos.vendedor_id','=',$bono->vendedor_id)
                            ->where('contratos.status','=',3)->count();

                $bono->ventaQuincena = Contrato::join('creditos','contratos.id','=','creditos.id')
                                    ->select('contratos.id')
                                    ->whereBetween('contratos.fecha', [$fechaIni, $fechaFin])
                                    ->where('creditos.vendedor_id','=',$bono->vendedor_id)
                                    ->where('contratos.status','!=',2)
                                    ->count();

                $bono->ventaAnt = Contrato::join('creditos','contratos.id','=','creditos.id')
                                    ->select('contratos.id')
                                    ->whereMonth('contratos.fecha',$mesAnt)
                                    ->whereYear('contratos.fecha',$anioAnt)
                                    ->where('creditos.vendedor_id','=',$bono->vendedor_id)
                                    ->where('contratos.status','=',3)->count();

            }
        }

        return [
            'pagination' => [
                'total'         => $bonos->total(),
                'current_page'  => $bonos->currentPage(),
                'per_page'      => $bonos->perPage(),
                'last_page'     => $bonos->lastPage(),
                'from'          => $bonos->firstItem(),
                'to'            => $bonos->lastItem(),
            ],
            'bonos'=>$bonos];
    }

    // Función para crear el segundo bono
    public function segundoBono(Request $request){

        $bono = new Bono_venta();
        $bono->contrato_id = $request->contrato_id;
        $bono->fecha_pago = $request->fecha_pago;
        $bono->monto = 1000;
        $bono->descripcion = $request->descripcion;
        $bono->num_bono = 2;
        $bono->save();

        //El bono se le asigna un status en 2 para indicar que ya fue creado el segundo.
        $primerBono = Bono_venta::findOrFail($request->id);
        $primerBono->status = 2;
        $primerBono->save();
    }

    // Función para retornar los comentarios del bono.
    public function listarObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_bono_venta::select('observacion','usuario','created_at')
        ->where('bono_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    // Funcion para almacenar comentario.
    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_bono_venta();
        $observacion->bono_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    // Función para imprimir el recibo de pago para el bono.
    public function reciboPDF($id){
        $bonos = Bono_venta::join('contratos','bonos_ventas.contrato_id','=','contratos.id')
        ->join('creditos','contratos.id','=','creditos.id')
        ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
        ->join('vendedores', 'creditos.vendedor_id', '=', 'vendedores.id')
        ->join('personal as c', 'clientes.id', '=', 'c.id')
        ->join('personal as v', 'vendedores.id', '=', 'v.id')
        ->select('contratos.id as folio',
                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS asesor"),
                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS cliente"),
                'bonos_ventas.id',
                'bonos_ventas.num_bono',
                'bonos_ventas.fecha_pago',
                'bonos_ventas.monto',
                'bonos_ventas.descripcion',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.num_lote',
                'creditos.manzana',
                'contratos.fecha',
                'inst_seleccionadas.tipo_credito'
        )->where('bonos_ventas.id','=',$id)
        ->where('inst_seleccionadas.elegido', '=', '1')
        ->get();

        $fechaDeposito = new Carbon($bonos[0]->fecha_pago);
        $bonos[0]->fecha_pago = $fechaDeposito->formatLocalized('%d/%m/%Y');

        $fecha = new Carbon($bonos[0]->fecha);
        $bonos[0]->fecha = $fecha->formatLocalized('%d/%m/%Y');

        $pdf = \PDF::loadview('pdf.reciboBonoVenta',['bonos' => $bonos]);
        return $pdf->stream('recibo_bono.pdf');
    }
}
