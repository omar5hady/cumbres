<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contrato;
use DB;

class BonoVentaController extends Controller
{
    public function indexContratos(Request $request){

        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')
                            ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                            ->join('personal as c', 'clientes.id', '=', 'c.id')
                            ->join('pagos_contratos as pc','contratos.id','=','pc.contrato_id')
                            ->select(
                                        'contratos.id',
                                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                        'creditos.fraccionamiento as proyecto',
                                        'creditos.etapa',
                                        'creditos.manzana',
                                        'creditos.num_lote',
                                        'creditos.precio_venta',
                                        'pc.num_pago','pc.fecha_pago','pc.pagado'
                                    )
                            ->where('pc.num_pago','=',0)
                            ->where('pc.pagado','=',2)
                            ->where('pc.tipo_pagare','=',0)
                            ->where('contratos.exp_bono','=',1)
                            ->orderBy('contratos.id','desc')
                            ->paginate(8);

        return [ 'contratos'=>$contratos ];
    }
}
