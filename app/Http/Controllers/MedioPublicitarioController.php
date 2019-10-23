<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medio_publicitario; //Importar el modelo
use Auth;

class MedioPublicitarioController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $medios_publicitarios = Medio_publicitario::orderBy('nombre','asc')->paginate(8);
        }
        else{
            $medios_publicitarios = Medio_publicitario::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $medios_publicitarios->total(),
                'current_page'  => $medios_publicitarios->currentPage(),
                'per_page'      => $medios_publicitarios->perPage(),
                'last_page'     => $medios_publicitarios->lastPage(),
                'from'          => $medios_publicitarios->firstItem(),
                'to'            => $medios_publicitarios->lastItem(),
            ],
            'medios_publicitarios' => $medios_publicitarios
        ];
    }

     //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $medio_publicitario = new Medio_publicitario();
        $medio_publicitario->nombre = $request->nombre;
        $medio_publicitario->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $medio_publicitario = Medio_publicitario::findOrFail($request->id);
        $medio_publicitario->nombre = $request->nombre;
        $medio_publicitario->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $medio_publicitario = Medio_publicitario::findOrFail($request->id);
        $medio_publicitario->delete();
    }

    public function selectMedioPublicitario(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $medios_publicitarios = Medio_publicitario::select('nombre','id')->get();
             return['medios_publicitarios' => $medios_publicitarios];

    }
}
