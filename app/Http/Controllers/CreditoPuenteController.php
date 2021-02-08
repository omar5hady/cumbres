<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Credito_puente;
use DB;
use Auth;

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
                ->where('lotes.contrato','=',0);
                //->where('lotes.habilitado','=',1);

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
        
        $lotes = $lotes->paginate(30);

        return $lotes;
    }

    public function getLotes(Request $request){
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.id','lotes.num_lote','lotes.fraccionamiento_id','lotes.etapa_id',
                'lotes.manzana', 'modelos.nombre as modelo',
                'etapas.num_etapa','fraccionamientos.nombre as proyecto')
            ->whereIn('lotes.id',$request->id)
            ->orderBy('lotes.num_lote')
            ->get();

        return ['lotes' => $lotes];
    }

    public function storeSolicitud(Request $request){
        
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $lotes = $request->lotes;

        try {
            DB::beginTransaction();
            $puente = new Credito_puente();
            $puente->banco = $request->banco;
            $puente->interes = $request->interes;
            $puente->apertura = $request->apertura;
            $puente->fraccionamiento = $request->fraccionamiento;
            $puente->etapa_id = $request->etapa_id;
            $puente->folio = $request->banco.'-'.$request->cantidad;
            $puente->save();

            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l->id);
                $lote->puente_id = $puente->id;
                $lote->credito_puente = $puente->folio;
                $lote->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
