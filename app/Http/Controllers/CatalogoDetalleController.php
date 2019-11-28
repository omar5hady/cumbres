<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cat_detalle_general;
use App\Cat_detalle_subconcepto;
use App\Cat_detalle;

use Illuminate\Support\Facades\DB;

class CatalogoDetalleController extends Controller
{
    public function indexGenerales(Request $request){
        if($request->general == '')
        {
            $general = General::orderBy('general,asc')->paginate(12);
        }
        else{
            $general = General::where('general','=',$request->general)->orderBy('general,asc')->paginate(12);
        }

        return [
            'pagination' => [
                'total'         => $general->total(),
                'current_page'  => $general->currentPage(),
                'per_page'      => $general->perPage(),
                'last_page'     => $general->lastPage(),
                'from'          => $general->firstItem(),
                'to'            => $general->lastItem(),
            ],
            'general' => $general
        ];
    }
}
