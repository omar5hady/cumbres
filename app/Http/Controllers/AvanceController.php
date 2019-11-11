<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avance;
use App\Partida;
use App\Licencia;
use App\Lote;
use DB;
use Excel;
use Auth;

class AvanceController extends Controller
{
    public function store($lote_id, $partida_id){
        if(Auth::user()->rol_id == 11)return redirect('/');
        $avance = new Avance();
        $avance->lote_id = $lote_id;
        $avance->partida_id = $partida_id;
        $avance->save();
    }

    public function indexProm(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar1 = $request->buscar1;
        $buscar2 = $request->buscar2;
        $criterio = $request->criterio;
        if($buscar=='' && $buscar1=='' && $buscar2==''){
        $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
            ->groupBy('avances.lote_id')
            ->where('lotes.aviso', '!=', '0')
            ->paginate(8);
        }
        else
        {
            if($criterio!='lotes.fraccionamiento_id'){
                $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                    DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                    'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                    'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->addSelect('fraccionamientos.nombre as proyecto')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
                ->groupBy('avances.lote_id')
                ->where('lotes.aviso', '!=', '0')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->paginate(8);
            }
            else{
                if($buscar1=='' && $buscar2 == ''){
                    $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                            DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                            'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->addSelect('fraccionamientos.nombre as proyecto')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
                        ->where('lotes.fraccionamiento_id', '=', $buscar)
                        ->where('lotes.aviso', '!=', '0')
                        ->groupBy('avances.lote_id')
                        ->paginate(8);
                    }
                    else{
                        if($buscar1==''){
                            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                                    DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                                    'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                                    'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
                                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                ->addSelect('fraccionamientos.nombre as proyecto')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
                                ->groupBy('avances.lote_id')
                                ->where('lotes.fraccionamiento_id', '=', $buscar)
                                ->where('lotes.num_lote', '=', $buscar2)
                                ->where('lotes.aviso', '!=', '0')
                                ->paginate(8);
                        }
                        else{
                            if($buscar2==''){
                                $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
                                    ->join('licencias','lotes.id','=','licencias.id')
                                    ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                                        DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                                        'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                                        'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->addSelect('fraccionamientos.nombre as proyecto')
                                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                                    ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
                                    ->groupBy('avances.lote_id')
                                    ->where('lotes.fraccionamiento_id', '=', $buscar)
                                    ->where('lotes.manzana', '=', $buscar1)
                                    ->where('lotes.aviso', '!=', '0')
                                    ->paginate(8);
                            }
                            else{
                                $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
                                    ->join('licencias','lotes.id','=','licencias.id')
                                    ->select('lotes.num_lote as lote', 'licencias.f_planos_obra',
                                        DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                                        'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso',
                                        'lotes.etapa_servicios','lotes.fecha_ini','lotes.fecha_fin','lotes.paquete', 'lotes.contrato')
                                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                    ->addSelect('fraccionamientos.nombre as proyecto')
                                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                                    ->addSelect('modelos.nombre as modelos', 'modelos.archivo','modelos.espec_obra')
                                    ->groupBy('avances.lote_id')
                                    ->where('lotes.fraccionamiento_id', '=', $buscar)
                                    ->where('lotes.manzana', '=', $buscar1)
                                    ->where('lotes.num_lote', '=', $buscar2)
                                    ->where('lotes.aviso', '!=', '0')
                                    ->paginate(8);
                            }
                        }
                        
                    }
    
            }
            
        }
            return [
                'pagination' => [
                    'total'         => $avance->total(),
                    'current_page'  => $avance->currentPage(),
                    'per_page'      => $avance->perPage(),
                    'last_page'     => $avance->lastPage(),
                    'from'          => $avance->firstItem(),
                    'to'            => $avance->lastItem(),
                ],
                'avance' => $avance
            ];
    }

    public function index(Request $request)
    {
        //condicion Ajax que evita ingresar a la vista sin pasar por la opcion correspondiente del menu
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if($buscar==''){
            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('partidas','avances.partida_id','=','partidas.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id'
            ,'partidas.partida','licencias.visita_avaluo','avances.partida_id','avances.cambio_avance','lotes.aviso')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')

            
            ->addSelect('lotes.contrato','lotes.firmado')
                ->where('lotes.aviso', '!=', '0')
                ->orderBy('avances.id','ASC')->distinct()->paginate(49);
        }
       else{
           if($criterio == 'avances.lote_id' || $criterio == 'lotes.id'){
            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('partidas','avances.partida_id','=','partidas.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id',
            'partidas.partida','licencias.visita_avaluo','avances.partida_id','avances.cambio_avance')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')
            
            ->addSelect('lotes.contrato','lotes.firmado')
            
                ->where($criterio, '=', $buscar)
                ->where('lotes.aviso', '!=', '0')
                ->orderBy('avances.id','ASC')->distinct()->paginate(49);
           }
           else{
            $avance = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('partidas','avances.partida_id','=','partidas.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id',
            'partidas.partida','licencias.visita_avaluo','avances.partida_id','avances.cambio_avance')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->addSelect('modelos.nombre as modelos')
            
            ->addSelect('lotes.contrato','lotes.firmado')
            
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->where('lotes.aviso', '!=', '0')
                ->orderBy('avances.id','ASC')->distinct()->paginate(49);
           }
            
       }

        return [
            'pagination' => [
                'total'         => $avance->total(),
                'current_page'  => $avance->currentPage(),
                'per_page'      => $avance->perPage(),
                'last_page'     => $avance->lastPage(),
                'from'          => $avance->firstItem(),
                'to'            => $avance->lastItem(),
            ],
            'avance' => $avance
        ];
    }

    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $avance = Avance::findOrFail($request->id);
        if($avance->avance > $request->avance)
            $avance->cambio_avance = 1;
        else
        $avance->cambio_avance = 0;
        $avance->avance = $request->avance;

        //porcentaje de avance: de acuerdo al campo avance (0 a 1) multiplicado por el porcentaje de la partida
        $partida = Partida::select('porcentaje')
            ->where('id','=',$avance->partida_id)->get();
        if($partida[0]->porcentaje == 0)
            $avance->avance_porcentaje = 0;
        else{
            $avance->avance_porcentaje = $partida[0]->porcentaje * $avance->avance;
        }
        $avance->save();
        
        $suma = Avance::select(DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"))
        ->where('avances.lote_id','=', $avance->lote_id)->get();
        //actualizacion del campo avance en la tabla licencias con la suma del porcentaje total
        $licencia = Licencia::findOrFail($avance->lote_id);
        $licencia->avance = $suma[0]->porcentajeTotal;
        $licencia->save();
    }

    public function excelLotesPartidas(Request $request, $contrato)
    {

            $partidas= Partida::select('partida')->distinct()->get();

            $avances = Avance::join('lotes','avances.lote_id','=','lotes.id')
            ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 'lotes.id',
            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id',
            'avances.partida_id','avances.cambio_avance','lotes.aviso')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->addSelect('fraccionamientos.nombre as proyecto')
                ->where('lotes.aviso', '=', $contrato)
                ->orderBy('lotes.num_lote','ASC')
                ->orderBy('avances.id','ASC')
                ->orderBy('lotes.id','ASC')->get();

            $totalPartidas = Avance::join('lotes','avances.lote_id','=','lotes.id')
                ->select('lotes.num_lote as lote','avances.avance', 'avances.avance_porcentaje', 'lotes.id',
                'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','avances.id',
                'avances.partida_id','avances.cambio_avance','lotes.aviso')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->addSelect('fraccionamientos.nombre as proyecto')
                ->where('lotes.aviso', '=', $contrato)->get()->count();

       $numRep = $totalPartidas/49;

        // return [
        //     'avance' => $avances,
        //     'totalPartidas' => $totalPartidas,
        //     'rep' => $numRep
        // ];

        return Excel::create('Avance_partidas', function($excel) use ($avances, $numRep, $partidas){
            $excel->sheet('avances', function($sheet) use ($avances, $numRep, $partidas){

                $sheet->row(1, [
                    'Proyecto: ', $avances[0]->proyecto, 'Contrato: ',$avances[0]->aviso
                ]);
                
                $sheet->row(2, [
                    'Partidas', 'Lotes:'
                ]);

                $sheet->cells('A1:AZ2', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(12);

                    // Set font weight to bold
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A3:AZ3', function ($cells) {
                    // Set font family
                    $cells->setFontFamily('Calibri');

                    // Set font size
                    $cells->setFontSize(10);

                    // Set font weight to bold
                    $cells->setAlignment('center');
                });

                $cont=3;

                for($i = 0; $i<49; $i++) {
                    $cont++;
                    $sheet->row($i+4, [
                        $partidas[$i]->partida,
                    ]);	
                }

                    $cel=$i+4;
                    $letra= 'B';
                    
                    for($j = 0; $j<$numRep; $j++)
                    for($i = 0; $i<49; $i++){
                        $cel=$i+4;
                        $mul = 49*(int)$j;
                        switch($j){
                            case '0':{
                                $letra = 'B';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '1':{
                                $letra = 'C';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '2':{
                                $letra = 'D';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '3':{
                                $letra = 'E';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '4':{
                                $letra = 'F';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%');
                                break;
                            }
                            case '5':{
                                $letra = 'G';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '6':{
                                $letra = 'H';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '7':{
                                $letra = 'I';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '8':{
                                $letra = 'J';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '9':{
                                $letra = 'K';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '10':{
                                $letra = 'L';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '11':{
                                $letra = 'M';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '12':{
                                $letra = 'N';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '13':{
                                $letra = 'O';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '14':{
                                $letra = 'P';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '15':{
                                $letra = 'Q';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '16':{
                                $letra = 'R';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '17':{
                                $letra = 'S';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '18':{
                                $letra = 'T';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '19':{
                                $letra = 'U';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '20':{
                                $letra = 'V';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '21':{
                                $letra = 'W';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '22':{
                                $letra = 'X';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '23':{
                                $letra = 'Y';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '24':{
                                $letra = 'Z';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '25':{
                                $letra = 'AA';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '26':{
                                $letra = 'AB';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '27':{
                                $letra = 'AC';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '28':{
                                $letra = 'AD';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '29':{
                                $letra = 'AE';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '30':{
                                $letra = 'AF';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '31':{
                                $letra = 'AG';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '32':{
                                $letra = 'AH';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '33':{
                                $letra = 'AI';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '34':{
                                $letra = 'AJ';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '35':{
                                $letra = 'AK';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '36':{
                                $letra = 'AL';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '37':{
                                $letra = 'AM';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '38':{
                                $letra = 'AN';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '39':{
                                $letra = 'AO';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '40':{
                                $letra = 'AP';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '41':{
                                $letra = 'AQ';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '42':{
                                $letra = 'AR';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                            case '43':{
                                $letra = 'AS';
                                $sheet->setCellValue($letra.'2', 'Lote: '.$avances[$mul]->lote); 
                                $sheet->setCellValue($letra.'3', 'Manzana: '.$avances[$mul]->manzana); 
                                $sheet->setCellValue($letra.$cel, $avances[$i + $mul]->avance_porcentaje.'%'); 
                                break;
                            }
                        }
                    }

                $num='A1:'.$letra . $cont;
                $sheet->setBorder($num, 'thin');
            });
        }
        
        )->download('xls');
    }


    public function exportExcel(Request $request)
    {
        $buscar = $request->buscar;
        $contrato = $request->contrato;
        if($contrato != ''){
            $avances = Avance::join('lotes','avances.lote_id','=','lotes.id')
                        ->select('lotes.num_lote as lote', 
                            DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->addSelect('fraccionamientos.nombre as proyecto')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->addSelect('modelos.nombre as modelo')
                        ->where('lotes.aviso', '=', $contrato)
                        ->groupBy('avances.lote_id')
                        ->get();
        }
        else{
            $avances = Avance::join('lotes','avances.lote_id','=','lotes.id')
                        ->select('lotes.num_lote as lote', 
                            DB::raw("SUM(avances.avance_porcentaje) as porcentajeTotal"), 
                            'lotes.fraccionamiento_id','lotes.manzana','lotes.modelo_id','avances.lote_id','lotes.aviso')
                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->addSelect('fraccionamientos.nombre as proyecto')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->addSelect('modelos.nombre as modelo')
                        ->where('lotes.fraccionamiento_id', '=', $buscar)
                        ->where('lotes.aviso', '!=', 0)
                        ->groupBy('avances.lote_id')
                        ->get();
        }
        
   
        
            return Excel::create('Avance_general', function($excel) use ($avances){
                $excel->sheet('avances', function($sheet) use ($avances){
                    
                    $sheet->row(1, [
                        'Clave.', 'Proyecto', 'Modelo', 'Manzana', 'Lote', 'Porcentaje de avance'
                    ]);


                    $sheet->cells('A1:F1', function ($cells) {
                        $cells->setBackground('#052154');
                        $cells->setFontColor('#ffffff');
                        // Set font family
                        $cells->setFontFamily('Calibri');

                        // Set font size
                        $cells->setFontSize(13);

                        // Set font weight to bold
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $sheet->setColumnFormat(array(
                        'F' => '0%'
                    ));

                    
                    $cont=1;

                    foreach($avances as $index => $avance) {
                        $cont++;
                        $avance->porcentajeTotal = $avance->porcentajeTotal/100;
                        $sheet->row($index+2, [
                            $avance->aviso, 
                            $avance->proyecto, 
                            $avance->modelo, 
                            $avance->manzana, 
                            $avance->lote,
                            $avance->porcentajeTotal
                        ]);	
                    }
                    $num='A1:F' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            
            )->download('xls');
      
    }
}
