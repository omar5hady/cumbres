<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notaria;

class NotariaController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $notarias = Notaria::orderBy('id','desc')->paginate(8);
        }
        else{
            $notarias = Notaria::where($criterio, 'like', '%'. $buscar . '%')
                                                ->orderBy('id','desc')->paginate(8);
        }

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

    public function store(Request $request){
        if(!$request->ajax())return redirect('/');

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

    public function update(Request $request){
        if(!$request->ajax())return redirect('/');
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

    public function destroy(Request $request){
        if(!$request->ajax())return redirect('/');
        $notarias =  Notaria::findOrFail($request->id);
        $notarias->delete();
    }

    public function select_notarias(Request $request){
        $estado = $request->estado;
        $ciudad = $request->ciudad;

        $notarias = Notaria::select('id','notaria','titular','colonia')
                             ->where('estado','=',$estado)
                             ->where('ciudad','=',$ciudad)
                             ->orderBy('id','asc')
                             ->get();

        return['notarias' => $notarias];
    }
}
