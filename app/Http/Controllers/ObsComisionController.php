<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use App\Obs_comision;
use Auth;
use Carbon\Carbon;

class ObsComisionController extends Controller
{
    // funcion para crear nueva  observacion en la tabla Obs_comision "Observaciones en comisiones "
    // se guarda el folio del contrato , el comentario y el id del usuario que hizo la observacion 
    public function store(Request $request)
    {               
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_comision();
        $observacion->contrato_id = $request->id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }
    
    // Funcion de consulta de observaciones registradas en la tabla Obs_comision 
    //filtradas por el folio del contrato 

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $id = $request->id;
        $observacion = Obs_comision::select('comentario','usuario','created_at','id')
                    ->where('contrato_id','=', $id)->orderBy('created_at','desc')->get();

        return [
            'observacion' => $observacion
        ];
    }
}
