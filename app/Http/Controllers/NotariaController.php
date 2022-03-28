<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notaria;
use Mockery\Matcher\Not;
use Auth;
//Controlador para el modelo Notaria
class NotariaController extends Controller
{
    //Función que retoran las notarias registradas
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar=='')
            $notarias = Notaria::orderBy('id','desc')->paginate(8);
        else //Busqueda general
            $notarias = Notaria::where($criterio, 'like', '%'. $buscar . '%')
                                                ->orderBy('id','desc')->paginate(8);

        return [
            'pagination' => [
                'total'         => $notarias->total(),
                'current_page'  => $notarias->currentPage(),
                'per_page'      => $notarias->perPage(),
                'last_page'     => $notarias->lastPage(),
                'from'          => $notarias->firstItem(),
                'to'            => $notarias->lastItem(),
            ],
            'notarias' => $notarias
        ];
    }
    //Función para registrar una nueva notaria
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $notarias = new Notaria();
        $notarias->notaria = $request->notaria;
        $notarias->titular = $request->titular;
        $notarias->estado = $request->estado;
        $notarias->ciudad = $request->ciudad;
        $notarias->colonia = $request->colonia;
        $notarias->cp = $request->cp;
        $notarias->direccion = $request->direccion;
        $notarias->telefono_1 = $request->telefono_1;
        $notarias->telefono_2 = $request->telefono_2;
        $notarias->telefono_3 = $request->telefono_3;
        $notarias->telefono_4 = $request->telefono_4;
        $notarias->save();
    }
    //Función para actualizar una notaria
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $notarias =  Notaria::findOrFail($request->id);
        $notarias->notaria = $request->notaria;
        $notarias->titular = $request->titular;
        $notarias->estado = $request->estado;
        $notarias->ciudad = $request->ciudad;
        $notarias->colonia = $request->colonia;
        $notarias->cp = $request->cp;
        $notarias->direccion = $request->direccion;
        $notarias->telefono_1 = $request->telefono_1;
        $notarias->telefono_2 = $request->telefono_2;
        $notarias->telefono_3 = $request->telefono_3;
        $notarias->telefono_4 = $request->telefono_4;
        $notarias->save();
    }
    //Función para eliminar una notaria
    public function destroy(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $notarias =  Notaria::findOrFail($request->id);
        $notarias->delete();
    }
    //Función que retorna las notarias registradas filtradas por estado y ciudad
    public function select_notarias(Request $request){
        if(!$request->ajax())return redirect('/');
        $estado = $request->estado;
        $ciudad = $request->ciudad;

        $notarias = Notaria::select('id','notaria','titular','colonia')
                             ->where('estado','=',$estado)
                             ->where('ciudad','=',$ciudad)
                             ->orderBy('id','asc')
                             ->get();

        return['notarias' => $notarias];
    }
    //Función para obtener los datos de una notaria.
    public function getDatosNotaria (Request $request){
        if(!$request->ajax())return redirect('/');
        $datos = Notaria::select('notaria','titular','direccion','colonia','cp')
                            ->where('id','=', $request->id)->get();
        
        return ['notarias' => $datos];
    }
}
