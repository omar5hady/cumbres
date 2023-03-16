<?php

namespace App\Http\Controllers\solicPagos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contrato;
use App\Etapa;

class ReportesController extends Controller
{
    public function getVentas(Request $request){

        $etapas = $this->getEtapas($request);
        if(sizeof($etapas)){
            foreach($etapas as $e){
                $e->ventas = Contrato::join('creditos as cr','cr.id','=','contratos.id')
                    ->join('lotes as l','cr.lote_id','=','l.id')
                    ->select('cr.etapa','cr.fraccionamiento as proyecto','cr.manzana','cr.precio_base',
                        'cr.precio_terreno_excedente as terreno_exc','cr.precio_obra_extra as obra_ext',
                        'cr.descuento_promocion',
                        'cr.costo_paquete','cr.precio_venta','cr.costo_descuento','cr.descuento_terreno',
                        'cr.descuento_ubicacion','l.num_lote','l.sublote','l.sobreprecio'
                    )
                    ->where('l.etapa_id','=',$e->id)
                    ->where('contratos.status','=',3);
                    if($request->f_ini != '' && $request->f_fin != '')
                        $e->ventas = $e->ventas->whereBetween('contratos.fecha', [$request->f_ini, $request->f_fin]);
                    $e->ventas = $e->ventas->get();

                $e->num_ventas = $e->ventas->count();
            }
        }

        return $etapas;

    }

    private function getEtapas(Request $request){
        $etapas = Etapa::join('fraccionamientos as f','f.id','=','etapas.fraccionamiento_id')
            ->select('etapas.id','etapas.num_etapa as etapa','f.nombre as proyecto')
            ->where('etapas.num_etapa','!=','Sin Asignar');
        if($request->proyecto != '')
            $etapas = $etapas->where('etapas.fraccionamiento_id','=',$request->proyecto);
        if($request->etapa != '')
            $etapas = $etapas->where('etapas.id','=',$request->etapa);

        return $etapas = $etapas->orderBy('f.nombre','asc')->paginate(10);

    }
}
