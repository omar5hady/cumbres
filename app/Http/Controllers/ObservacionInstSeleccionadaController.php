<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Obs_inst_selec;
use App\inst_seleccionada;
use App\Obs_cobroCredito;
use Auth;

class ObservacionInstSeleccionadaController extends Controller
{
    // se deshabilita esta funcion
    // 
    public function select_ultima(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;;
        
            $observacion = Obs_inst_selec::
            select('comentario','usuario','created_at')
                    ->where('inst_selec_id','=', $buscar)->orderBy('created_at','desc')
                    ->first();

            return ['observacion' => $observacion];
       
    }

    // Funcion deshabilitada 
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_inst_selec();
        $observacion->inst_selec_id = $request->institucion_seleccionada_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }

    // Funcion de consulta de informacion de la tabla Obs_inst_selec donde se optiene los comentarios , usuario , y la fecha de creacion
    // donde se filtra por el id de la institucion   
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_inst_selec::select('comentario','usuario','created_at')
                    ->where('inst_selec_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }

    // Funcion para crear una nueva Observacion en la tabla de Obs_cobroCredito el modulo CobroCredito hace la peticion
    // donde se guarda el id de lote  el comentario y el id del usuario , 
    public function storeCobro(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_cobroCredito();
        $observacion->credito_id = $request->credito_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }


    // Funcion de consulta de datos de la tabla Obs_cobroCredito donde se optiene los comentarios , usuario , y la fecha de creacion
    // donde se filtra por el id del credito 
    public function indexCobro(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_cobroCredito::select('id','comentario','usuario','created_at')
                    ->where('credito_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

        return [
            'pagination' => [
                'total'         => $observacion->total(),
                'current_page'  => $observacion->currentPage(),
                'per_page'      => $observacion->perPage(),
                'last_page'     => $observacion->lastPage(),
                'from'          => $observacion->firstItem(),
                'to'            => $observacion->lastItem(),
            ],
            'observacion' => $observacion
        ];
    }
}
