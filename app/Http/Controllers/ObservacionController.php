<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Observacion;
use App\Lote;
use Auth;

class ObservacionController extends Controller
{
   
    // Funcion para consultar el ultimo comentario(Observacion) donde el modulo de ( ActaDeterminacion  y Licencias) hacen peticion 
    // por el id de lote , opteniendo el comentario , el usuario y la fecha de creacion
    public function select_ultima(Request $request)
    {
        
        if(!$request->ajax())return redirect('/'); //*

        $buscar = $request->buscar;;
        
       
            $observacion = Observacion::
            select('comentario','usuario','created_at')
                    ->where('lote_id','=', $buscar)->orderBy('created_at','desc')
                    ->first();

            return ['observacion' => $observacion];
       
    }

     // Funcion para crear observaciones de los  modulos ( Acta de terminacion, licencias , y lote ) 
     //donde se guarda el id de lote  el comentario y el id del usuario
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/'); //*
        $observacion = new Observacion();
        $observacion->lote_id = $request->lote_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }

    // Funcion de consulta para optener las observaciones donde los modulos ( ActaDeTerminacion y Licencias) 
    // hacen la consulta , filtrando por el id de lote y seleccionando el comentario , el usuario y la fecha de creacion  
    public function index(Request $request){
        if(!$request->ajax())return redirect('/'); //*
        $buscar = $request->buscar;
        $observacion = Observacion::select('comentario','usuario','created_at')
                    ->where('lote_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

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

// * condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
