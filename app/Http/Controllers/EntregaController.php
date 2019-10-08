<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrega;
use App\Obs_entrega;
use DB;
use Auth;
use App\Expediente;

class EntregaController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax())return redirect('/');

        try{
            DB::beginTransaction();
            $entrega = new Entrega();
            $entrega->id = $request->id;
            $entrega->save();

            $expediente = Expediente::findOrFail($request->id);
            $expediente->postventa = 1;
            $expediente->save();

            $observacion = new Obs_entrega();
            $observacion->entrega_id = $request->id;
            $observacion->comentario = $request->comentario;
            $observacion->usuario = Auth::user()->usuario;
            $observacion->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }       
    }
}
