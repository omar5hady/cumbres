<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Obs_solic_equipamiento;
use Auth;

class ObsservacionEquipamientoController extends Controller
{

    // Funcion para crear una nueva observacion  en la que los modulos ( Equipamietos , 
    // ObraEquipameintos, SegInstalacion, Equipamientos) utilizan la funcion onde guardan 
    // el id de la solicitud , el comentario, el id del usuario
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_solic_equipamiento();
        $observacion->solic_id = $request->solic_id;
        $observacion->comentario = $request->comentario;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();


    }


    // Funcion para hacer la consulta de datos de la tabla Obs_solic_equipamiento en la que los modulos ( Equipamietos , 
    // ObraEquipameintos, SegInstalacion, Equipamientos) utilizan la funcion donde se hacen la consulta de los datos
    // ( comentario, usuario, y la fecha de creacion)
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $observacion = Obs_solic_equipamiento::select('comentario','usuario','created_at')
                    ->where('solic_id','=', $buscar)->orderBy('created_at','desc')->paginate(20);

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
