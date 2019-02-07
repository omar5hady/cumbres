<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Observacion;
use App\Lote;
use Auth;

class ObservacionController extends Controller
{

    public function select_ultima(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        //if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;;
        
       
            $observacion = Observacion::
            select('comentario','usuario','created_at')
                    ->where('lote_id','=', $buscar)->orderBy('created_at','desc')
                    ->first();

            return ['observacion' => $observacion];
       
    }

    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $observacion = new Observacion();
        $observacion->lote_id = $request->lote_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }
    public function index(Request $request){
        $buscar = $request->buscar;;
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
