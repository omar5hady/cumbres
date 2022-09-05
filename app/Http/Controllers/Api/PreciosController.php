<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Lote;

class PreciosController extends Controller
{
    public function index(Request $request){
        $inventario = Lote::join('fraccionamientos as p','lotes.fraccionamiento_id','=','p.id')
                            ->join('etapas as e','lotes.etapa_id','=','e.id')
                            ->join('modelos as m','lotes.modelo_id','=','m.id')
                            ->select('p.nombre as proyecto','e.num_etapa as etapa','m.nombre as prototipo',
                                'lotes.manzana', 'lotes.num_lote', 'lotes.sublote','lotes.calle','lotes.numero',
                                'lotes.interior','lotes.terreno','lotes.precio_base','lotes.excedente_terreno',
                                'lotes.sobreprecio','m.construccion','m.terreno as terreno_m','lotes.ajuste'
                            )
                            ->where('lotes.habilitado','=',1)
                            ->where('lotes.apartado','=',0)
                            ->where('lotes.contrato','=',0)
                            ->where('lotes.casa_muestra','=',0)
                            ->where('lotes.casa_renta','=',0);

                if($request->modelo != '')
                    $inventario = $inventario->where('m.nombre','like','%'.$request->modelo.'%');

                if($request->proyecto != '')
                    $inventario = $inventario->where('p.nombre','like','%'.$request->proyecto.'%');

                if($request->privada != '')
                    $inventario = $inventario->where('e.num_etapa','like','%'.$request->privada.'%');

        $inventario = $inventario->orderBy('m.nombre','asc')
                            ->get();

        return $inventario;
    }

}
