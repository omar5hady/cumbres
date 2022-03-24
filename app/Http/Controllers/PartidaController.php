<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partida;
use App\Avance;
use App\Lote;
use DB;
use Auth;

class PartidaController extends Controller
{
    //  Funcion para hacer la consulta de datos de la tabla "Partida" 
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;

        // consulta para optener los datos 
        $query = Partida::join('modelos','partidas.modelo_id','=','modelos.id')
            ->select('modelos.nombre as modelo','partidas.partida', 'partidas.costo', 
            'partidas.porcentaje','modelos.fraccionamiento_id','partidas.modelo_id','partidas.id')
            ->join('fraccionamientos','modelos.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto');
        
        $partidas = $query;
    
            if($buscar != '')
                $partidas = $partidas->where($criterio, '=', $buscar);  // se filtra por el tipo de criterio 
            if($buscar2 != '')
                $partidas = $partidas->where('modelos.nombre',  'like', '%'. $buscar2 . '%'); //  con una segunda variable se hace la busqueda por nombre
                
        $partidas = $partidas->orderBy('partidas.id','ASC')->paginate(49);
            
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

    // funcion para crear nuevo registro en tabla " Partida " donde solo se agrega el nombre de la partida y el Id 
    public function store($id, $nombre)
    {
        //if(!$request->ajax())return redirect('/');
        $partida = new Partida();
        $partida->partida = $nombre;
        $partida->modelo_id = $id;
        $partida->save();
    }

    // funcion mas especifica para crear nuevo registro donde se guarda el costo ,la partida y el modelo id 

    public function registrar(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $partida = new Partida();
        $partida->partida = $request->partida;
        $partida->modelo_id = $request->modelo_id;
        $partida->costo = $request->costo;
        $partida->save();

        $this->actualizarPorcentaje($request->modelo_id); //se ejecuta la funcion mandando como parametro el modelo id 
    }                                                     // para actualizar porcentaje

    // Funcion para actualizar informacion de la tabla
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $partida = Partida::findOrFail($request->id); // se hace la busqueda del id del registro 
        $partida->partida = $request->partida; 
        $partida->costo = $request->costo;
        $partida->save();

        $this->actualizarPorcentaje($request->modelo_id); //se ejecuta la funcion mandando como parametro el modelo id 
    }                                                     // para actualizar porcentaje

    // funcion para actualizar datos de "partida" tomando como dato de referencia el modelo id
    public function actualizarPorcentaje($modelo){
        $sumaTotal=Partida::select(DB::raw("SUM(partidas.costo) as costoTotal"))  // primero se hace la sumatoria total de cada partida 
            ->where('partidas.modelo_id','=', $modelo)->get();                    // y crea un alias de costo total
        
        $partidas=Partida::where('partidas.modelo_id','=',$modelo)->get();     // se crea una nueva variable de las partidas filtradas por el modelo id
        
        foreach($partidas as $index => $partida) {    // se recorre la variable partidas 
            if($partida->costo>0){  //verifica si la partida tiene costo mayor a cero 
                $partida->porcentaje = ($partida->costo/$sumaTotal[0]->costoTotal)*100;  // calcula el porcentaje
                $avances = Avance::where('partida_id','=',$partida->id)->get(); // de la tala avance se selecciona la partida con su id
                foreach($avances as $key => $avance){
                    $avance->avance_porcentaje = $partida->porcentaje * $avance->avance; // se actualiza el avance de la partida 
                    $avance->save();
                }
            }
            else
                $partida->porcentaje = 0; // en caso de que el costo de la partida sea cero se setean en cero el porcentaje
            $partida->save();
        }

            return ['partidas' => $partidas];
    }

    // Funcion para eliminar un registro de la tabla "partida" y se ejecuta la funcion de "actualizarPorcentaje"
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $partida = Partida::findOrFail($request->id);
        $partida->delete();

        $this->actualizarPorcentaje($request->modelo_id);
    }
}
