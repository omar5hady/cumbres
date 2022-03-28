<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lugar_contacto;
use Auth;
//Controlador para el modelo Lugar_contacto.
class LugarContactoController extends Controller
{
    //Función que retorna los lugares de contacto registrados
    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
            $lugares_contacto = Lugar_contacto::orderBy('nombre','asc')->paginate(8);
        else
            $lugares_contacto = Lugar_contacto::where($criterio, 'like', '%'. $buscar . '%')->orderBy('nombre','asc')->paginate(8);

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

     //Función para registrar un nuevo lugar de contacto
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lugar_contacto = new Lugar_contacto();
        $lugar_contacto->nombre = $request->nombre;
        $lugar_contacto->usuario = Auth::user()->usuario;
        $lugar_contacto->save();
    }

    //funcion para actualizar un lugar de contacto
    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $lugar_contacto = Lugar_contacto::findOrFail($request->id);
        $lugar_contacto->nombre = $request->nombre;
        $lugar_contacto->save();
    }
    //Función para eliminar un lugar de contacto.
    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $lugar_contacto = Lugar_contacto::findOrFail($request->id);
        $lugar_contacto->delete();
    }
    //Función que retorna todos los lugares de contacto 
    public function selectLugar_contacto(Request $request){
             //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
             if(!$request->ajax())return redirect('/');
             $lugares_contacto = Lugar_contacto::select('nombre','id')->get();
             return['lugares_contacto' => $lugares_contacto];

    }
}
