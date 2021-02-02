<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use DB;

class CreditoPuenteController extends Controller
{
    public function indexSinCredito( Request $request){

        $lotes = $this->getSinCredito($request);

        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes
        ];

    }

    private function getSinCredito($request){
        $lotes = Lote::join('fraccionamientos as f','lotes.fraccionamiento_id','=','f.id')
                ->join('etapas as e','lotes.etapa_id','=','e.id')
                ->join('modelos as m','lotes.modelo_id','=','m.id')
                ->select('lotes.id', 'lotes.manzana', 'lotes.num_lote', 'lotes.sublote',
                    'lotes.habilitado','lotes.credito_puente', 'lotes.terreno',
                    'lotes.emp_terreno', 'lotes.emp_constructora','lotes.sublote','lotes.calle',
                    'lotes.numero', 'lotes.interior',
                    'm.nombre as modelo', 'e.num_etapa','f.nombre as proyecto'
                )
                ->where('lotes.credito_puente','=',NULL)
                ->where('lotes.habilitado','=',0);

                if($request->proyecto != '')
                    $lotes = $lotes->where('lotes.fraccionamiento_id','=',$request->proyecto);

                if($request->etapa != '')
                    $lotes = $lotes->where('lotes.etapa_id','=',$request->etapa);

                if($request->modelo != '')
                    $lotes = $lotes->where('lotes.modelo_id','=',$request->modelo);

                if($request->manzana != '')
                    $lotes = $lotes->where('lotes.manzana','like','%'.$request->manzana.'%');

                if($request->lote != '')
                    $lotes = $lotes->where('lotes.num_lote','=',$request->lote);

                if($request->emp_terreno != '')
                    $lotes = $lotes->where('lotes.emp_terreno','=',$request->emp_terreno);

                if($request->emp_constructora != '')
                    $lotes = $lotes->where('lotes.emp_constructora','=',$request->emp_constructora);
        
        $lotes = $lotes->paginate(15);

        return $lotes;
    }
}
