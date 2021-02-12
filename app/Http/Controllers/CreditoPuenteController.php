<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lote;
use App\Modelo;
use App\Credito_puente;
use App\Lote_puente;
use App\Precio_puente;
use DB;
use Auth;

class CreditoPuenteController extends Controller
{
    public function indexSinCredito( Request $request){
        if(!$request->ajax())return redirect('/');

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
                ->where('lotes.contrato','=',0);
                //->where('lotes.habilitado','=',1);

                if($request->puente == ''){
                    $lotes = $lotes->where('lotes.credito_puente','=',NULL);
                }
                else{
                    $lotes = $lotes->where('lotes.credito_puente','=',$request->puente);
                }

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
        if(!$request->ajax())return redirect('/');
        $lotes = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.id','lotes.num_lote','lotes.fraccionamiento_id','lotes.etapa_id',
                'lotes.manzana', 'modelos.nombre as modelo','lotes.modelo_id',
                'etapas.num_etapa','fraccionamientos.nombre as proyecto')
            ->whereIn('lotes.id',$request->id)
            ->orderBy('lotes.num_lote')
            ->get();

        return ['lotes' => $lotes];
    }

    public function getModelosPuente(Request $request){
        if(!$request->ajax())return redirect('/');
        $modelos = Modelo::select('id','nombre')->where('fraccionamiento_id','=',$request->id)
                ->where('nombre','!=','Por Asignar')->orderBy('nombre','asc')->get();

                return ['modelos'=>$modelos];
    }

    public function storeSolicitud(Request $request){
        
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $lotes = $request->lotes;
        $modelos = $request->modelos;

        try {
            DB::beginTransaction();
            $puente = new Credito_puente();
            $puente->banco = $request->banco;
            $puente->interes = $request->interes;
            $puente->apertura = $request->apertura;
            $puente->total = $request->total;
            $puente->fraccionamiento = $request->fraccionamiento;
            $puente->folio = $request->banco.'-'.$request->cantidad;
            $puente->save();

            $id = $puente->id;

            foreach ($modelos as $index => $m) {
                $precio = new Precio_puente();
                $precio->solicitud_id = $id;
                $precio->modelo_id = $m['id'];
                $precio->precio = $m['precio'];
                $precio->save();
            }

            foreach ($lotes as $index => $l) {
                $lote = Lote::findOrFail($l['id']);
                $lote->puente_id = $id;
                $lote->credito_puente = 'EN PROCESO';
                $lote->save();

                $lote_puente = new Lote_puente();
                $lote_puente->solicitud_id = $id;
                $lote_puente->lote_id = $l['id'];
                $lote_puente->modelo_id = $l['modelo_id'];
                $lote_puente->precio_p = $l['precio'];
                $lote_puente->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function indexCreditos(Request $request){
        $creditos = Credito_puente::join('fraccionamientos','creditos_puente.fraccionamiento','=','fraccionamientos.id')
                    ->select('creditos_puente.id','creditos_puente.banco','creditos_puente.folio','creditos_puente.interes',
                                'creditos_puente.status','creditos_puente.total','creditos_puente.apertura',
                                'fraccionamientos.nombre as proyecto','creditos_puente.fraccionamiento'    
                            )
                    ->orderBy('creditos_puente.id','desc')
                    ->paginate(10);

        return [
            'pagination' => [
                'total'         => $creditos->total(),
                'current_page'  => $creditos->currentPage(),
                'per_page'      => $creditos->perPage(),
                'last_page'     => $creditos->lastPage(),
                'from'          => $creditos->firstItem(),
                'to'            => $creditos->lastItem(),
            ],
            'creditos' => $creditos
        ];
    }

}
