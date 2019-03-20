<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Dato_extra;
use App\Credito;

class EstadisticasController extends Controller
{
    public function estad_datos_extra(Request $request)
    {

        $proyecto = $request->buscar;

        $edades = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->select(DB::raw('SUM(datos_extra.rang010) as sum010'),
                    DB::raw('SUM(datos_extra.rang1120) as sum1120'),
                    DB::raw('SUM(datos_extra.rang21) as sum21'))
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->get();
        
        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->where('lotes.fraccionamiento_id',$proyecto)
        ->where('datos_extra.mascota','=',0)
        ->get()->count();

        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
        ->join('lotes','creditos.lote_id','=','lotes.id')
        ->select(DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                    DB::raw('SUM(datos_extra.num_perros) as perros'))
        ->where('lotes.fraccionamiento_id',$proyecto)
        ->get();

        $mascotas[0]->sin_mascotas = $SinMascotas;
        $mascotas[0]->promedioPerros = $mascotas[0]->perros/($mascotas[0]->sin_mascotas + $mascotas[0]->sumMascota);
 

        return ['edades'=>$edades,'mascotas'=>$mascotas];      
 
    }
}
