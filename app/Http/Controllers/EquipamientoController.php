<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipamiento;

class EquipamientoController extends Controller
{

    public function index(Request $request){

            $equipamientos = Equipamiento::select('id','proveedor_id','equipamiento')
                ->where('proveedor_id','=', $request->proveedor_id)->orderBy('equipamiento','asc')
                    ->paginate(20);
        

        return [
            'pagination' => [
                'total'         => $equipamientos->total(),
                'current_page'  => $equipamientos->currentPage(),
                'per_page'      => $equipamientos->perPage(),
                'last_page'     => $equipamientos->lastPage(),
                'from'          => $equipamientos->firstItem(),
                'to'            => $equipamientos->lastItem(),
            ],
            'equipamientos' => $equipamientos
        ];

    }

    public function store(Request $request){

        $equipamiento = new Equipamiento();
        $equipamiento->proveedor_id = $request->proveedor_id;
        $equipamiento->equipamiento = $request->equipamiento;
        $equipamiento->save();
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $equipamiento = Equipamiento::findOrFail($request->id);
        $equipamiento->delete();
    }
}
