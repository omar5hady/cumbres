<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipo_credito;
use Auth;

class TipoCreditoController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $Tipos_creditos = Tipo_credito::orderBy('nombre','asc')->paginate(8);
        }
        else{
            $Tipos_creditos = Tipo_credito::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $Tipos_creditos->total(),
                'current_page'  => $Tipos_creditos->currentPage(),
                'per_page'      => $Tipos_creditos->perPage(),
                'last_page'     => $Tipos_creditos->lastPage(),
                'from'          => $Tipos_creditos->firstItem(),
                'to'            => $Tipos_creditos->lastItem(),
            ],
            'Tipos_creditos' => $Tipos_creditos
        ];
    }

     //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $Tipo_credito = new Tipo_credito();
        $Tipo_credito->nombre = $request->nombre;
        $Tipo_credito->institucion_fin = $request->institucion_fin;
        $Tipo_credito->save();
    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $Tipo_credito = Tipo_credito::findOrFail($request->id);
        $Tipo_credito->nombre = $request->nombre;
        $Tipo_credito->institucion_fin = $request->institucion_fin;
        $Tipo_credito->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $Tipo_credito = Tipo_credito::findOrFail($request->id);
        $Tipo_credito->delete();
    }

    public function select_credito(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $Tipos_creditos = Tipo_credito::select('nombre','id')
             ->where('institucion_fin','=',$request->institucion_fin)
             ->get();
             return['Tipos_creditos' => $Tipos_creditos];
    }

    public function select_tipoCredito(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $Tipos_creditos = Tipo_credito::select('nombre')
        ->distinct()
        ->get();
        return['Tipos_creditos' => $Tipos_creditos];
}

    public function select_institucion(Request $request){
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $credito = $request->buscar;

        $instituciones = Tipo_credito::select('institucion_fin')
        ->where('nombre','=',$credito)
        ->distinct()
        ->get();

        return['instituciones' => $instituciones];
    }


}
