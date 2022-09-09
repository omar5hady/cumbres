<?php

namespace App\Http\Controllers\Privada;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Amenitie;

use Auth;
use DB;

class AmenidadesController extends Controller
{
    public function store(Request $request){
        $amenidad = new Amenitie();
        $amenidad->amenidad = $request->amenidad;
        $amenidad->etapa_id = $request->etapa_id;
        $amenidad->save();

        return($amenidad->id);
    }

    public function destroy(Request $request){
        $amenidad = Amenitie::findOrFail($request->id);
        $amenidad->delete();
    }
}
