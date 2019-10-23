<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InstitucionFinanciamiento;
use Auth;

class InstitucionFinanciamientoController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $instituciones_financiamiento = InstitucionFinanciamiento::orderBy('nombre','asc')->paginate(8);
        }
        else{
            $instituciones_financiamiento = InstitucionFinanciamiento::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $instituciones_financiamiento->total(),
                'current_page'  => $instituciones_financiamiento->currentPage(),
                'per_page'      => $instituciones_financiamiento->perPage(),
                'last_page'     => $instituciones_financiamiento->lastPage(),
                'from'          => $instituciones_financiamiento->firstItem(),
                'to'            => $instituciones_financiamiento->lastItem(),
            ],
            'instituciones_financiamiento' => $instituciones_financiamiento
        ];
    }

     //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $InstitucionFinanciamiento = new InstitucionFinanciamiento();
        $InstitucionFinanciamiento->nombre = $request->nombre;
        $InstitucionFinanciamiento->save();
    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $InstitucionFinanciamiento = InstitucionFinanciamiento::findOrFail($request->id);
        $InstitucionFinanciamiento->nombre = $request->nombre;
        $InstitucionFinanciamiento->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $InstitucionFinanciamiento = InstitucionFinanciamiento::findOrFail($request->id);
        $InstitucionFinanciamiento->delete();
    }

    public function select_institucion_financiamiento(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $instituciones_financiamiento = InstitucionFinanciamiento::select('nombre','id')->get();
             return['instituciones_financiamiento' => $instituciones_financiamiento];
    }
}
