<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $roles = Rol::orderBy('nombre','asc')->paginate(5);
        }
        else{
            $roles = Rol::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','asc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'      => $roles->perPage(),
                'last_page'     => $roles->lastPage(),
                'from'          => $roles->firstItem(),
                'to'            => $roles->lastItem(),
            ],
            'roles' => $roles
        ];

    }


    public function store(Request $request){
        if(!$request->ajax())return redirect('/');
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->condicion = $request->condicion;
        $rol->save();

    }

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');
        $rol = Rol::findOrFail($request->id);
        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->condicion = $request->condicion;
        $rol->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $rol = Rol::findOrFail($request->id);
        $rol->delete();
    }

 
    public function selectRol(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $roles = Rol::where('condicion', '=', '1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();
 
        return ['roles' => $roles];
    } 
}
