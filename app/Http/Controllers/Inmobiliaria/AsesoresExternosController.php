<?php

namespace App\Http\Controllers\Inmobiliaria;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AsesorExterno;
use DB;
use Carbon\Carbon;
use Auth;

class AsesoresExternosController extends Controller
{
    public function index(Request $request){
        $asesor = AsesorExterno::join('inmobiliarias as i', 'i.id', '=', 'asesor_externos.mobiliaria_id' )
        ->select('asesor_externos.*', 'i.nombre as inmobiliaria', 'i.logo')
        ->where('asesor_externos.mobiliaria_id','=',$request->inmobiliaria_id)
        ->where(
            DB::raw("CONCAT(asesor_externos.nombre,' ',asesor_externos.apellido)"), 'like', '%'. $request->nombre . '%'
        )
        ->paginate(3);

        foreach($asesor as $a){
            $inmobiliaria = explode(' ', $a->inmobiliaria);
            $a->inmobiliaria_1 = $inmobiliaria[0];
            $a->inmobiliaria_2 = implode(' ', array_slice($inmobiliaria, 1));
        }

        return $asesor;
    }

    public function store(Request $request){
        $id = $request->id;
        if($id == 0){
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
        if(Auth::user()->rol_id == 2 && $id == 0){
            $asesor->f_ini = Carbon::now();;
            $asesor->f_fin = Carbon::now()->addMonth(6);
        }
        else{
            $asesor->f_ini = $request->f_ini;
            $asesor->f_fin = $request->f_fin;
        }

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
