<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partida;
use App\Avance;
use App\Lote;
use DB;

class PartidaController extends Controller
{

    /*public function selectPartidas($modelo_id){
        $partidas = Partida::select('id','partida')
            ->where('modelo_id','=',$modelo_id)->get();
        
            return $partidas;
    }*/

    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $partidas = Partida::join('modelos','partidas.modelo_id','=','modelos.id')
            ->select('modelos.nombre as modelo','partidas.partida', 'partidas.costo', 
            'partidas.porcentaje','modelos.fraccionamiento_id','partidas.modelo_id','partidas.id')
            ->join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')->addSelect('fraccionamientos.nombre as proyecto')
                ->orderBy('partidas.id','ASC')->paginate(49);
        }
       else{
           if($buscar2==''){
                $partidas = Partida::join('modelos','partidas.modelo_id','=','modelos.id')
                ->select('modelos.nombre as modelo','partidas.partida', 'partidas.costo', 
                'partidas.porcentaje','modelos.fraccionamiento_id','partidas.modelo_id','partidas.id')
                ->join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')->addSelect('fraccionamientos.nombre as proyecto')
                    ->where($criterio, 'like', '%'. $buscar . '%')
                    ->orderBy('partidas.id','ASC')->paginate(49);
           }
           else{
                $partidas = Partida::join('modelos','partidas.modelo_id','=','modelos.id')
                ->select('modelos.nombre as modelo','partidas.partida', 'partidas.costo', 
                'partidas.porcentaje','modelos.fraccionamiento_id','partidas.modelo_id','partidas.id')
                ->join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')->addSelect('fraccionamientos.nombre as proyecto')
                    ->where($criterio, '=', $buscar)
                    ->where('modelos.nombre',  'like', '%'. $buscar2 . '%')
                    ->orderBy('partidas.id','ASC')->paginate(49);
            }
        }

        return [
            'pagination' => [
                'total'         => $partidas->total(),
                'current_page'  => $partidas->currentPage(),
                'per_page'      => $partidas->perPage(),
                'last_page'     => $partidas->lastPage(),
                'from'          => $partidas->firstItem(),
                'to'            => $partidas->lastItem(),
            ],
            'partidas' => $partidas
        ];
    }

    public function store($id, $nombre)
    {
        //if(!$request->ajax())return redirect('/');
        $partida = new Partida();
        $partida->partida = $nombre;
        $partida->modelo_id = $id;
        $partida->save();
    }

    public function registrar(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $partida = new Partida();
        $partida->partida = $request->partida;
        $partida->modelo_id = $request->modelo_id;
        $partida->costo = $request->costo;
        $partida->save();

        $this->actualizarPorcentaje($request->modelo_id);
    }

    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $partida = Partida::findOrFail($request->id);
        $partida->costo = $request->costo;
        $partida->save();

        $this->actualizarPorcentaje($request->modelo_id);
    }

    public function actualizarPorcentaje($modelo){
        $sumaTotal=Partida::select(DB::raw("SUM(partidas.costo) as costoTotal"))
            ->where('partidas.modelo_id','=', $modelo)->get();
        
        $partidas=Partida::where('partidas.modelo_id','=',$modelo)->get();
        
        foreach($partidas as $index => $partida) {
            if($partida->costo>0){
                $partida->porcentaje = ($partida->costo/$sumaTotal[0]->costoTotal)*100;
                $avances = Avance::where('partida_id','=',$partida->id)->get();
                foreach($avances as $key => $avance){
                    $avance->avance_porcentaje = $partida->porcentaje * $avance->avance;
                    $avance->save();
                }
            }
            else
                $partida->porcentaje = 0;
            $partida->save();
        }

            return ['partidas' => $partidas];
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $partida = Partida::findOrFail($request->id);
        $partida->delete();

        $this->actualizarPorcentaje($request->modelo_id);
    }
}
