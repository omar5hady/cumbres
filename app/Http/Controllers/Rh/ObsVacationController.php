<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ObsVacation;
use Auth;

class ObsVacationController extends Controller
{
    public function index(Request $request){
        $obs = ObsVacation::where('vacacion_id','=', $request->id)->get();
        return $obs;
    }

    public function store(Request $request){
        $this->saveObservation($request->id, $request->observacion);
    }

    public function saveObservation($id, $observacion){
        $obs = new ObsVacation();
        $obs->usuario = Auth::user()->usuario;
        $obs->observacion = $observacion;
        $obs->vacacion_id = $id;
        $obs->save();
    }
}
