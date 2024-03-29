<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Etapa;
use App\Fraccionamiento;
use DB;

/* Controlador utilizado para la obtencion de privadas que administra el sistema. 
    esta información se usa para la app movil.
*/

class PrivadasController extends Controller
{
    public function index(){
        $privadas = Etapa::select('id','num_etapa as privada','fraccionamiento_id')
                    ->where('num_etapa','!=','Sin Asignar')
                    ->where('num_etapa','!=','EXTERIOR')
                    ->get();

        foreach ($privadas as $key => $privada) {
            $privada->fraccionamiento = Fraccionamiento::select('id','nombre as fraccionamiento')
            ->where('id','=',$privada->fraccionamiento_id)->first();
        }

        return $privadas;
    }
    
}
