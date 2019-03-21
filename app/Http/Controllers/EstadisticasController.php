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
                ->where('creditos.status','=',2)
                ->get();
        
        $discapacitados = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->where('datos_extra.persona_discap','=',1)
                ->where('creditos.status','=',2)
                ->get()->count();
        
        $silla_ruedas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->where('datos_extra.silla_ruedas','=',1)
                ->where('creditos.status','=',2)
                ->get()->count();

        
        $SinMascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->where('datos_extra.mascota','=',0)
                ->where('creditos.status','=',2)
                ->get()->count();

        $mascotas = Dato_extra::join('creditos','datos_extra.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->select(
                            DB::raw('SUM(datos_extra.ama_casa) as totalAmaCasa'),
                            DB::raw('SUM(datos_extra.num_vehiculos) as totalAutos'),
                            DB::raw('SUM(datos_extra.mascota) as sumMascota'),
                            DB::raw('SUM(datos_extra.num_perros) as perros')
                        )
                ->where('lotes.fraccionamiento_id',$proyecto)
                ->where('creditos.status','=',2)
                ->get();

        
        $mascotas[0]->sin_mascotas = $SinMascotas;
        $totalPersonas = $mascotas[0]->sin_mascotas + $mascotas[0]->sumMascota;
        $sinDiscap =$totalPersonas - $discapacitados;
        $mascotas[0]->promedioPerros = $mascotas[0]->perros/$totalPersonas;
        $promedioAutos = $mascotas[0]->totalAutos/$totalPersonas;
        $promedioAmasCasa = $mascotas[0]->totalAmaCasa/$totalPersonas;
 

        return ['edades'=>$edades,'mascotas'=>$mascotas, 
                'discap'=>$discapacitados, 
                'sinDiscap'=> $sinDiscap,
                'silla_ruedas'=>$silla_ruedas,
                'promedioAutos'=>$promedioAutos,
                'promedioAmasCasa'=>$promedioAmasCasa];      
    }
}
