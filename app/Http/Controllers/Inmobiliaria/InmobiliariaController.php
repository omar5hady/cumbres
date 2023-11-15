<?php

namespace App\Http\Controllers\Inmobiliaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inmobiliaria;
use App\AsesorExterno;
use App\Vendedor;
use Auth;

class InmobiliariaController extends Controller
{
    public function index(Request $request){
        $inmobiliarias = Inmobiliaria::select('*');

        if(Auth::user()->rol_id == 2){
            $vendedor = Vendedor::findOrFail(Auth::user()->id);
            $inmobiliarias = $inmobiliarias->where('nombre','=',$vendedor->inmobiliaria);
        }else{
            $inmobiliarias = $inmobiliarias->where('nombre','like','%'.$request->buscar.'%');
        }
        $inmobiliarias = $inmobiliarias->orderBy('nombre','asc')->paginate(10);


        foreach($inmobiliarias as $i){
            $i->asesores = AsesorExterno::where('mobiliaria_id','=',$i->id)->count();
        }

        return $inmobiliarias;
    }

    public function store(Request $request){
        if($request->id == 0){
            $inmobiliaria = new Inmobiliaria();
            if($request->logo != ''){
                $fileName = uniqid().'.'.$request->logo->getClientOriginalExtension();
                $moved =  $request->logo->move(public_path('/img/logos'), $fileName);
                $inmobiliaria->logo = $fileName;
            }
        }
        else{
            $inmobiliaria = Inmobiliaria::findOrFail($request->id);
            if($request->logo != ''){
                $fileName = uniqid().'.'.$request->logo->getClientOriginalExtension();
                $moved =  $request->logo->move(public_path('/img/logos'), $fileName);
                if($inmobiliaria->logo != NULL){
                    $pathAnterior = public_path().'/img/logos/'.$inmobiliaria->logo; // elimina la imagen anterior
                    File::delete($pathAnterior);
                }
                $inmobiliaria->logo = $fileName;
            }
        }
        $inmobiliaria->nombre = $request->nombre;

        $inmobiliaria->save();
    }

    public function destroy(Request $request){
        $inmobiliaria = Inmobiliaria::findOrFail($request->id);
        $pathAnterior = public_path().'/img/logos/'.$inmobiliaria->logo;
        if($inmobiliaria->logo != NULL){
            File::delete($pathAnterior);
        }
        $inmobiliaria->delete();
    }

    public function selectInmobiliarias(Request $request){
        return Inmobiliaria::orderBy('nombre','asc')->get();
    }
}
