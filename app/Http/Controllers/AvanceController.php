<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avance;
use App\Partida;
use App\Licencia;
use App\Lote;
use DB;

class AvanceController extends Controller
{
    public function store($lote_id, $partida_id){
        $avance = new Avance();
        $avance->lote_id = $lote_id;
        $avance->partida_id = $partida_id;
        $avance->save();
    }

    public function indexProm(Request $request){
        $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->select('lotes.num_lote as lote', 
                DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')
            ->groupBy('avances.lote_id')
            ->paginate(8);

            return [
                'pagination' => [
                    'total'         => $avance->total(),
                    'current_page'  => $avance->currentPage(),
                    'per_page'      => $avance->perPage(),
                    'last_page'     => $avance->lastPage(),
                    'from'          => $avance->firstItem(),
                    'to'            => $avance->lastItem(),
                ],
                'avance' => $avance
            ];
    }

    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('partidas','avances.partida_id','=','partidas.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id'
            ,'partidas.partida','avances.partida_id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')
                ->orderBy('avances.id','ASC')->paginate(49);
        }
       else{
            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('partidas','avances.partida_id','=','partidas.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id',
            'partidas.partida','avances.partida_id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')
            
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('avances.id','ASC')->paginate(49);
       }

        return [
            'pagination' => [
                'total'         => $avance->total(),
                'current_page'  => $avance->currentPage(),
                'per_page'      => $avance->perPage(),
                'last_page'     => $avance->lastPage(),
                'from'          => $avance->firstItem(),
                'to'            => $avance->lastItem(),
            ],
            'avance' => $avance
        ];
    }
    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $avance = Avance::findOrFail($request->id);
        $avance->avance = $request->avance;

        //porcentaje de avance: de acuerdo al campo avance (0 a 1) multiplicado por el porcentaje de la partida
        $partida = Partida::select('porcentaje')
            ->where('id','=',$avance->partida_id)->get();
        if($partida[0]->porcentaje == 0)
            $avance->avance_porcentaje = 0;
        else{
            $avance->avance_porcentaje = $partida[0]->porcentaje * $avance->avance;
        }
        $avance->save();
        
        $suma = Avance::select(DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"))
        ->where('avances.lote_id','=', $avance->lote_id)->get();
        //actualizacion del campo avance en la tabla licencias con la suma del porcentaje total
        $licencia = Licencia::findOrFail($avance->lote_id);
        $licencia->avance = $suma[0]->porcentajeTotal;
        $licencia->save();


    }
}
