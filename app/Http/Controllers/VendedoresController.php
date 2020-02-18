<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;
use App\User;
use App\Cliente;
use Carbon\Carbon;

class VendedoresController extends Controller
{
    public function ventasVendedorReporte(Request $request){
        $vendedores = User::join('personal','users.id','=','personal.id')
                ->join('vendedores','personal.id','vendedores.id')
                ->select('personal.id',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS vendedor"),
                        'vendedores.tipo','vendedores.inmobiliaria')
                ->orderBy('vendedor','asc')->get();


        if(sizeOf($vendedores)){
            foreach ($vendedores as $ve => $vendedor) {
                $vendedor->atendio = Cliente::where('vendedor_id','=',$vendedor->id)
                        ->where('clasificacion','!=',7)->count();
            }
        }

        
        return['vendedores'=>$vendedores];
    }
}
