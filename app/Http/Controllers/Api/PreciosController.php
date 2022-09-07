<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Lote;
use App\Modelo;
use App\Fraccionamiento;
use App\Etapa;

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
                            ->where('m.nombre','!=','Terreno')
                            ->where('lotes.apartado','=',0)
                            ->where('lotes.contrato','=',0)
                            ->where('lotes.casa_muestra','=',0)
                            ->where('lotes.casa_renta','=',0);

                if($request->modelo != '')
                    $inventario = $inventario->where('m.nombre','like','%'.$request->modelo.'%');

                if($request->proyecto != '' &&  $request->privada != ''){
                    $ids = $this->getIdsLotesDisp($request->proyecto, $request->privada);
                    $inventario = $inventario->whereIn('lotes.id',$ids);
                        //->where('p.nombre','like','%'.$request->proyecto.'%');
                        //->where('lotes.excedente_terreno','=',0);
                }


                // if($request->privada != ''){
                //     $inventario = $inventario->where('e.num_etapa','like','%'.$request->privada.'%');
                //         //->where('lotes.excedente_terreno','=',0);
                // }


        $inventario = $inventario->orderBy('m.nombre','asc')
                            ->orderBy('lotes.excedente_terreno');

                    if($request->modelo != '')
                        $inventario = $inventario
                            ->limit(8)->get();
                    else
                        $inventario = $inventario->distinct()->get();

        return $inventario;
    }

    private function getIdsLotesDisp($proyecto, $etapa){
        $ids=[];
        $i=0;
        $fra = Fraccionamiento::select('id')->where('nombre','like','%'.$proyecto.'%')->get();
        $et = Etapa::select('id')->where('num_etapa','like','%'.$etapa.'%')->whereIn('fraccionamiento_id',$fra)->first();
        $modelos = Modelo::select('id')->whereIn('fraccionamiento_id',$fra)->get();

        foreach($modelos as $modelo){
            $lotes = Lote::select('id')->where('modelo_id','=',$modelo->id)
                ->where('lotes.habilitado','=',1)
                ->where('lotes.apartado','=',0)
                ->where('lotes.contrato','=',0)
                ->where('lotes.casa_muestra','=',0)
                ->where('lotes.casa_renta','=',0)
                ->whereIn('etapa_id',$et)->limit(2)->get();
            if(sizeof($lotes)){
                foreach($lotes as $lote){
                    $ids[$i] = $lote->id;
                    $i++;
                }
            }
        }

        return $ids;
    }

}
