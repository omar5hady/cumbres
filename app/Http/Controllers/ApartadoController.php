<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartado;
use App\Lote;
use App\Personal;
use DB;
use Auth;

class ApartadoController extends Controller
{
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $apartado = new Apartado();
        $apartado->lote_id = $request->lote_id;
        $apartado->vendedor_id = $request->vendedor_id;
        $apartado->cliente_id = $request->cliente_id;
        $apartado->fecha_apartado = $request->fecha_apartado;
        $apartado->tipo_credito = $request->tipo_credito;
        $apartado->comentario = $request->comentario;
        $apartado->save();

        $lote = Lote::findOrFail($request->lote_id);
        $lote->apartado = $apartado->id;
        $lote->save();

        
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $apartado = Apartado::findOrFail($request->id);
        $apartado->lote_id = $request->lote_id;
        $apartado->vendedor_id = $request->vendedor_id;
        $apartado->cliente_id = $request->cliente_id;
        $apartado->fecha_apartado = $request->fecha_apartado;
        $apartado->tipo_credito = $request->tipo_credito;
        $apartado->comentario = $request->comentario;
        $apartado->save();

        
    }

    public function destroy(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $apartado = Apartado::findOrFail($request->id);
        $apartado->delete();
        
        $lote = Lote::findOrFail($request->lote_id);
        $lote->apartado = 0;
        $lote->save();


    }

    public function select_datos_apartado(Request $request){
        if(!$request->ajax())return redirect('/');
        $apartados = Apartado::select('cliente_id','vendedor_id','tipo_credito','fecha_apartado','comentario')
                ->where('lote_id','=',$request->lote_id)
                ->get();
        $persona = Personal::select(DB::raw("CONCAT(nombre,' ',apellidos) AS n_completo"))
        ->where('id','=',$apartados[0]->cliente_id)
        ->get();

        $apartados[0]->cliente = $persona[0]->n_completo;

        return ['apartados' => $apartados];
                               
    }

}
