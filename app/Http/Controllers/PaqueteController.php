<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paquete;
use DB;
use Carbon\Carbon;
use App\Etapa;
use Auth;

class PaqueteController extends Controller
{

    // Funcion para consulta de datos  de la tabla Paquete donde hace busqueda por la relacion con las tablas 
    // Fraccionamientos y Etapas,  
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $current = Carbon::today()->format('ymd');
        $criterio = $request->criterio;

        $query = Paquete::join('fraccionamientos','paquetes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','paquetes.etapa_id','=','etapas.id')
                ->select('etapas.num_etapa as etapa','fraccionamientos.nombre as fraccionamiento','paquetes.id',
                    'paquetes.fraccionamiento_id','paquetes.etapa_id','paquetes.nombre','paquetes.v_ini','paquetes.v_fin',
                    'paquetes.costo','paquetes.descripcion',
                    DB::raw('(CASE WHEN paquetes.v_fin >= ' . $current . ' THEN 1 ELSE 0 END) AS is_active')); 
                    // condicion para seleccionar solo los paquetes que esten activos con fecha mayor o gual a la actual 
                                                                                                        
        
        if($buscar==''){
            $paquetes = $query;
        }
        elseif($buscar != '' && $buscar2 == ''){
            if($criterio == 'paquetes.nombre'){
                $paquetes = $query
                    ->where($criterio, 'like','%'. $buscar.'%');
            }
            else{
                $paquetes = $query
                    ->where($criterio, '=', $buscar);
            }
            
        }
        else{
            $paquetes = $query
                ->where($criterio, '=', $buscar)
                ->where('etapas.id', '=', $buscar2);
        }

        $paquetes = $paquetes->orderBy('is_active', 'desc')
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('paquetes.nombre','asc')
                                ->paginate(20);


        return [
            'pagination' => [
                'total'         => $paquetes->total(),
                'current_page'  => $paquetes->currentPage(),
                'per_page'      => $paquetes->perPage(),
                'last_page'     => $paquetes->lastPage(),
                'from'          => $paquetes->firstItem(),
                'to'            => $paquetes->lastItem(),
            ],
            'paquetes' => $paquetes
        ];
    }

    //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $paquetes = new Paquete();
        $paquetes->fraccionamiento_id = $request->fraccionamiento_id;
        $paquetes->etapa_id = $request->etapa_id;
        $paquetes->nombre = $request->nombre;
        $paquetes->v_ini = $request->v_ini;
        $paquetes->v_fin = $request->v_fin;
        $paquetes->costo = $request->costo;
        $paquetes->descripcion = $request->descripcion;
        $paquetes->save();
    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $paquetes = Paquete::findOrFail($request->id);
        $paquetes->fraccionamiento_id = $request->fraccionamiento_id;
        $paquetes->etapa_id = $request->etapa_id;
        $paquetes->nombre = $request->nombre;
        $paquetes->v_ini = $request->v_ini;
        $paquetes->v_fin = $request->v_fin;
        $paquetes->costo = $request->costo;
        $paquetes->descripcion = $request->descripcion;
    
        $paquetes->save();
    }

    // Funcion para eliminar una relacion " Paquete " buscando por el id del paquete
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $paquetes = Paquete::findOrFail($request->id);
        $paquetes->delete();
    }

    // Funcion para seleccionar el paquete por los criterios seleccionados 
    public function select_paquetes(Request $request){
        if(!$request->ajax())return redirect('/');
        $current = Carbon::now()->toDateString();
        $buscar = $request->buscar;
        $proyecto = $request->proyecto;

        // primero se optiene el numero de etapa por el fraccionamiento seleccionado
        $fraccionamiento = Etapa::join('fraccionamientos','fraccionamientos.id','=','etapas.fraccionamiento_id')
                            ->select('etapas.id')
                            ->where('fraccionamientos.nombre','=',$proyecto)
                            ->where('etapas.num_etapa','=',$buscar)->get();

        // se filtran los "paquetes" por las fechas de vencimiento 
        $paquetes = Paquete::join('etapas','paquetes.etapa_id','=','etapas.id')
                            ->select('paquetes.id','paquetes.nombre','paquetes.descripcion','paquetes.costo','paquetes.v_ini','paquetes.v_fin')
                            ->where('etapas.id','=',$fraccionamiento[0]->id)
                            ->whereDate('paquetes.v_fin','>=',$current)
                            ->whereDate('paquetes.v_ini','<=',$current)
                            ->get();

        return['paquetes' => $paquetes];
    }

    // Funcion para la consulta de datos del "paquete" seleccionado 
    // filtrado por el paquete id 
    public function select_datos_paquetes(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $datos_paquetes = Paquete::select('paquetes.descripcion','paquetes.costo','paquetes.v_ini','paquetes.v_fin','paquetes.nombre')
                          ->where('paquetes.id','=',$buscar)
                          ->get();

        return['datos_paquetes' => $datos_paquetes];

    }


}
