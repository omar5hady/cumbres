<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lugar_contacto;
use Auth;

class LugarContactoController extends Controller
{
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $lugares_contacto = Lugar_contacto::orderBy('nombre','asc')->paginate(8);
        }
        else{
            $lugares_contacto = Lugar_contacto::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);
        }

        return [
            'pagination' => [
                'total'         => $lugares_contacto->total(),
                'current_page'  => $lugares_contacto->currentPage(),
                'per_page'      => $lugares_contacto->perPage(),
                'last_page'     => $lugares_contacto->lastPage(),
                'from'          => $lugares_contacto->firstItem(),
                'to'            => $lugares_contacto->lastItem(),
            ],
            'lugares_contacto' => $lugares_contacto
        ];
    }

     //funcion para insertar en la tabla
    public function store(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $lugar_contacto = new Lugar_contacto();
        $lugar_contacto->nombre = $request->nombre;
        $lugar_contacto->usuario = Auth::user()->usuario;
        $lugar_contacto->save();
    }

    //funcion para actualizar los datos
    public function update(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lugar_contacto = Lugar_contacto::findOrFail($request->id);
        $lugar_contacto->nombre = $request->nombre;
        $lugar_contacto->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $lugar_contacto = Lugar_contacto::findOrFail($request->id);
        $lugar_contacto->delete();
    }

    public function selectLugar_contacto(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $lugares_contacto = Lugar_contacto::select('nombre','id')->get();
             return['lugares_contacto' => $lugares_contacto];

    }
}
