<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Etapa;
use App\Fraccionamiento;
use DB;

class PrivadasController extends Controller
{
    public function index(){
        $privadas = Etapa::select('id','num_etapa as privada','fraccionamiento_id')
                    ->where('num_etapa','!=','Sin Asignar')
                    ->get();

        foreach ($privadas as $key => $privada) {
            $privada->fraccionamiento = Fraccionamiento::select('id','nombre as fraccionamiento')
            ->where('id','=',$privada->fraccionamiento_id)->first();
        }

        return $privadas;
    }
    
}
