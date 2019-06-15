<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto_admin;
use App\Avaluo;

class GastosAdministrativosController extends Controller
{
    public function storeAvaluo(Request $request){
        $gasto = new Gasto_admin();
        $gasto->contrato_id = $request->id;
        $gasto->concepto = 'Avaluo';
        $gasto->costo = $request->costo;
        $gasto->fecha = $request->fecha;
        $gasto->observacion = '';
        $gasto->save();

        $avaluo = Avaluo::findOrFail($request->avaluoId);
        $avaluo->costo = $request->costo;
        $avaluo->save();
    }

    public function getDatosAvaluo(Request $request){
        $gasto = Gasto_admin::select('id','fecha')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->where('costo','=',$request->costo)
                ->get();
            
        return ['gasto' => $gasto];
    }

    public function updateAvaluo(Request $request){
        $gasto = Gasto_admin::findOrFail($request->gasto_id);
        $gasto->costo = $request->costo;
        $gasto->fecha = $request->fecha;
        $gasto->save();

        $avaluo = Avaluo::findOrFail($request->avaluoId);
        $avaluo->costo = $request->costo;
        $avaluo->save();
    }
}
