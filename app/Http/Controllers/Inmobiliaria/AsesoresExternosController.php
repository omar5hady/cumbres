<?php

namespace App\Http\Controllers\Inmobiliaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AsesorExterno;
use DB;

class AsesoresExternosController extends Controller
{
    public function index(Request $request){
        $asesor = AsesorExterno::where('mobiliaria_id','=',$request->inmobiliaria_id)
        ->where(
            DB::raw("CONCAT(nombre,' ',apellido)"), 'like', '%'. $request->nombre . '%'
        )
        ->paginate(3);

        return $asesor;
    }

    public function store(Request $request){
        if($request->id == 0){
            $asesor = new AsesorExterno();
            if($request->photo != ''){
                $fileName = uniqid().'.'.$request->foto->getClientOriginalExtension();
                $moved =  $request->foto->move(public_path('/img/externos'), $fileName);
                $asesor->photo = $fileName;
            }
        }
        else{
            $asesor = AsesorExterno::findOrFail($request->id);
            if($request->photo != ''){
                $fileName = uniqid().'.'.$request->photo->getClientOriginalExtension();
                $moved =  $request->photo->move(public_path('/img/externos'), $fileName);
                if($asesor->photo != NULL){
                    $pathAnterior = public_path().'/img/externos/'.$asesor->photo; // elimina la imagen anterior
                    File::delete($pathAnterior);
                }
                $asesor->photo = $fileName;
            }
        }
        $asesor->mobiliaria_id=$request->mobiliaria_id;
        $asesor->nombre=$request->nombre;
        $asesor->apellido=$request->apellido;
        $asesor->direccion=$request->direccion;
        $asesor->celular=$request->celular;
        $asesor->f_ini = $request->f_ini;
        $asesor->f_fin = $request->f_fin;

        $asesor->save();
    }

    public function destroy(Request $request){
        $asesor = AsesorExterno::findOrFail($request->id);
        if($asesor->photo != NULL){
            $pathAnterior = public_path().'/img/externos/'.$asesor->photo; // elimina la imagen
            File::delete($pathAnterior);
        }
        $asesor->delete();
    }
}
