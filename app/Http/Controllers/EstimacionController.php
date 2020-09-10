<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use PHPExcel_Worksheet_Drawing;
use Auth;
use App\Ini_obra;
use App\Lote;
use App\Estimacion;
use App\Fg_estimacion;
use App\Anticipo_estimacion;
use App\Hist_estimacion;
use App\Http\Controllers\IniObraController;
use Carbon\Carbon;

class EstimacionController extends Controller
{
    public function prueba(Request $request){
        //$obraC = new IniObraController();
        $fraccionamiento = 4;
        $contratista = 25527;
        $constructora = 'Grupo Constructor Cumbres';

        $iniObras = Ini_obra::select('id')
            ->where('fraccionamiento_id','=',$fraccionamiento)
            ->where('emp_constructora','=',$constructora)
            ->where('contratista_id','=',$contratista)->get();

        $contratos = $this->getContratos(25527, 4, 'Grupo Constructor Cumbres');
        $fondoGarantia = $this->getFG($iniObras);
        $anticipos = $this->getAnticipos($iniObras);
        $titulo = 'Resumen de estimaciones Fraccionamiento ';

        if(sizeof($contratos)){
            $titulo = 'Resumen de estimaciones Fraccionamiento '.$contratos[0]->proyecto;
            foreach ($contratos as $index => $contrato) {
                $anterior = $this->calculos($contrato->id);
                $contrato->estimadoAnt = $anterior['totalAnt'];
                $contrato->estimadoAct = $anterior['totalAct'];
                $contrato->numEst = $anterior['num'];
            }
        }

      

        return Excel::create($titulo , function($excel) use ($fondoGarantia, $anticipos, $contratos){
            $excel->sheet($contratos[0]->proyecto, function($sheet) use ($fondoGarantia, $anticipos, $contratos){
                
                $sheet->mergeCells('A1:S1');
                $sheet->mergeCells('A2:S2');
                $sheet->mergeCells('A3:S3');
                
                
                $sheet->setSize('A1', 25, 80);
                $sheet->setSize('A4', 25, 50);
                $sheet->setSize('B1', 20, 20);
                $sheet->setSize('C1', 15, 20);
                $sheet->setSize('D1', 15, 20);
                $sheet->setSize('H1', 25, 20);
                $sheet->setSize('I1', 25, 20);
                $sheet->setSize('J1', 15, 20);
                $sheet->setSize('K1', 25, 20);
                $sheet->setSize('L1', 25, 20);
                $sheet->setSize('E7', 20, 20);
                $sheet->setSize('F7', 20, 20);
                $sheet->setSize('G7', 20, 20);
                $sheet->setSize('M1', 25, 20);
                $sheet->setSize('N1', 25, 20);
                $sheet->setSize('O1', 25, 20);
                $sheet->setSize('P1', 25, 20);
                $sheet->setSize('Q1', 25, 20);
                $sheet->setSize('R1', 25, 20);
    
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                if($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
                if($contratos[0]->emp_constructora == 'CONCRETANIA')
                    $objDrawing->setPath(public_path('img/contratos/logoConcretaniaObra.png')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);

                if($contratos[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $sheet->cell('A1', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(16);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                if($contratos[0]->emp_constructora == 'CONCRETANIA');
                    $sheet->cells('A1:A3', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'CONCRETANIA, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(16);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });

                    
                    
                $sheet->row(2, [
                    'Resumen de estimaciones Fraccionamiento '.'"'.$contratos[0]->proyecto.'"'
                ]);

                $sheet->row(3, [
                    $contratos[0]->contratista
                ]);

                $sheet->row(5, [
                    'Contrato', 'Etapa contable', 'Num de Viv', 'Monto en Presup.', 'Importe contrato', 'Estimado pago Ant.', 'Amort. Anterior',
                    'F. Garantia Ant', 'Neto Pagado (Ant)', 'Estim. No.', 'Estimado este pago', 'Amort. Este pago', 'F.G. Este pago', 'Neto este pago',
                    'Importe acum', 'Amortiz acum', 'F.G. Acum', 'Neto Acum', 'Importe por estimar'
                ]);

                $ini=5;


                $sheet->cells('A5:S5', function ($cells) {
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
                    
                $cont=6;

                $sheet->setColumnFormat(array(
                    'E' => '$#,##0.00',
                    'F' => '$#,##0.00',
                    'G' => '$#,##0.00',
                    'H' => '$#,##0.00',
                    'I' => '$#,##0.00',
                    'K' => '$#,##0.00',
                    'L' => '$#,##0.00',
                    'M' => '$#,##0.00',
                    'N' => '$#,##0.00',
                    'O' => '$#,##0.00',
                    'P' => '$#,##0.00',
                    'Q' => '$#,##0.00',
                    'R' => '$#,##0.00',
                    'S' => '$#,##0.00'
                ));

                $suma0 = $suma1 = $suma2 = $suma3 = $suma4 = $suma5 = 0;
                $suma6 = $suma7 = $suma8 = $suma9 = $suma10 = $suma11 = $suma12 = $suma13 = 0;

                $suma14 = $suma15  = 0;
                
                //////////////////// VIVIENDAS ///////////////////////
                    foreach($contratos as $index => $detalle) {
                        
                        $amortAnt = $detalle->estimadoAnt*($detalle->anticipo/100);
                        $fondoG = $detalle->estimadoAnt*($detalle->porc_garantia_ret/100);
                        $netoPagado = $amortAnt + $fondoG + $detalle->estimadoAnt;

                        $amortAct = $detalle->estimadoAct*($detalle->anticipo/100);
                        $fondoGAct = $detalle->estimadoAct*($detalle->porc_garantia_ret/100);
                        $netoAct = $amortAct + $fondoGAct + $detalle->estimadoAct;

                        $importeAcum = $detalle->estimadoAnt + $detalle->estimadoAct;
                        $amortAcum = $amortAnt + $amortAct;
                        $fgAcum = $fondoG + $fondoGAct;
                        $netoAcum = $netoPagado + $netoAct;

                        $suma0+=$detalle->num_casas;
                        $suma1+=$detalle->total_importe;
                        $suma2+=$detalle->estimadoAnt;
                        $suma3+=$amortAnt;
                        $suma4+=$fondoG;
                        $suma5+=$netoPagado;
                        $suma6+=$detalle->estimadoAct;
                        $suma7+=$amortAct;
                        $suma8+=$fondoGAct;
                        $suma9+=$netoAct;
                        $suma10+=$importeAcum;
                        $suma11+=$amortAcum;
                        $suma12+=$fgAcum;
                        $suma13+=$netoAcum;

                        $cont++;    
                        
                        $sheet->cells('J'.$cont.':N'.$cont, function ($cells) {
                            $cells->setFontWeight('bold');
                        });


                        $sheet->row($cont, [
                            $detalle->clave, 
                            $detalle->clave, 
                            $detalle->num_casas, 
                            'Monto en Presup',

                            $detalle->total_importe, 
                            $detalle->estimadoAnt, 
                            $amortAnt,
                            $fondoG,
                            $netoPagado,

                            $detalle->numEst,

                            $detalle->estimadoAct,
                            $amortAct,
                            $fondoGAct,
                            $netoAct,

                            $importeAcum,
                            $amortAcum,
                            $fgAcum, 
                            $netoAcum, 
                            'importe Por estimar'
                        ]);	  
                        
                    }
                    $cont++;
                    $sheet->row($cont, [
                        'Totales Vivienda', 
                        '', 
                        $suma0, 
                        '',

                        $suma1, 
                        $suma2, 
                        $suma3,
                        $suma4,
                        $suma5,

                        '',

                        $suma6,
                        $suma7,
                        $suma8,
                        $suma9,

                        $suma10,
                        $suma11,
                        $suma12, 
                        $suma13, 
                        ''
                    ]);	  

                    $sheet->setBorder('A'.$ini.':S'.$cont, 'thin');

                    $sheet->cell('A'.$cont, function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                    $sheet->cells('K'.$cont.':S'.$cont, function ($cells) {
                        $cells->setFontWeight('bold');
                    });
                    $cont+=2;
                    $ini = $cont;

                ///////////////////// URBANIZACION ///////////////////
                    $sheet->cells('J'.$cont.':N'.$cont, function ($cells) {
                        $cells->setFontWeight('bold');
                    });


                    $sheet->row($cont, [
                        '', '', '', '', '', '', '', '', '', 
                        '', '', '', '', '', '', '', '', '', ''
                    ]);	  

                    $cont++;
                    $sheet->row($cont, [
                        'Total de Urbanización', 
                        '','','','','','','','','',
                        '','','','','','','','',''
                    ]);	  

                    $sheet->setBorder('A'.$ini.':S'.$cont, 'thin');

                    $sheet->cells('A'.$cont, function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                    $sheet->cells('K'.$cont.':S'.$cont, function ($cells) {
                        $cells->setFontWeight('bold');
                    });
                    $cont+=2;

                    $sheet->cells('A'.$cont, function($cell) {
                        // manipulate the cell
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });

                    $sheet->cells('K'.$cont.':S'.$cont, function ($cells) {
                        $cells->setFontWeight('bold');
                    });

                    $sheet->row($cont, [
                        'TOTAL = ', '', '',  '', '',  '',  '', '', '', 
                        '', '', '', '', '', '', '', '',  '', ''
                    ]);	

                    $sheet->setBorder('A'.$cont.':S'.$cont, 'thin');

                    $cont+=2;
                    $sheet->mergeCells('A'.$cont.':B'.$cont);

                ///////////// INFORMACIÓN ADICIONAL
                    $sheet->cell('A'.$cont, function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'INFORMACION ADICIONAL');
                        $cell->setFontSize(11);
                        $cell->setFontWeight('bold');
                    
                    });

                    if(sizeOf($anticipos)){
                        $cont++;  
                        $ini = $cont;
                        $sheet->mergeCells('A'.$cont.':B'.$cont);
                        $sheet->mergeCells('C'.$cont.':E'.$cont);
                        $sheet->mergeCells('F'.$cont.':G'.$cont);
                        $sheet->mergeCells('H'.$cont.':I'.$cont);
                        $sheet->cells('C'.$cont.':I'.$cont, function($cell) {
                        
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        
                        });

                        $sheet->row($cont, [
                            '',
                            '',
                            'MONTO DE ANTICIPO',
                            '',
                            '',
                            'ANTICIPO POR AMORTIZAR',
                            '',
                            'FECHA PAGO DEL ANTICIPO'
                        ]);	 
                        $ant = '';
                        $cont++;

                        foreach($anticipos as $index => $detalle) {

                            $suma14 += $detalle->monto_anticipo;
                            
                            $sheet->mergeCells('C'.$cont.':E'.$cont);
                            $sheet->mergeCells('F'.$cont.':G'.$cont);
                            $sheet->mergeCells('H'.$cont.':I'.$cont);

                            $sheet->setColumnFormat(array(
                                'C'.$cont.':E'.$cont => '$#,##0.00',
                                'H'.$cont.':I'.$cont => 'dd-mm-yyyy'
                            ));

                            $sheet->row($cont, [
                                $detalle->clave, 
                                '', 
                                $detalle->monto_anticipo, 
                                '',
                                '',

                                '',
                                '',
                                $detalle->fecha_anticipo
                            ]);	
                            $cont++;
                            
                        }
                        

                        $sheet->row($cont, [
                            '', '', $suma14, '','','','',''
                        ]);	 

                        $sheet->mergeCells('H'.$cont.':I'.$cont);

                        $sheet->setColumnFormat(array(
                            'C'.$cont.':E'.$cont => '$#,##0.00',
                        ));
                        $sheet->mergeCells('C'.$cont.':E'.$cont);

                        $sheet->cells('C'.$cont.':E'.$cont, function($cell) {
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        });

                        $sheet->setBorder('A'.$ini.':I'.$cont, 'thin');

                    }


                ///////////// Fondo de Garantia
                

                if(sizeOf($fondoGarantia)){
                    $cont+=2;  
                    $ini = $cont;
                    $sheet->mergeCells('A'.$cont.':B'.$cont);
                    $sheet->mergeCells('C'.$cont.':E'.$cont);
                   
                    

                    $sheet->row($cont, [
                        'Fecha de entrega','','','','','Monto'
                    ]);	 
                    $cont++;

                    foreach($fondoGarantia as $index => $detalle) {

                        $suma15 += $detalle->monto_fg;
                        
                        $sheet->mergeCells('A'.$cont.':B'.$cont);
                        $sheet->mergeCells('C'.$cont.':E'.$cont);

                        $sheet->setColumnFormat(array(
                            'F'.$cont.':G'.$cont => '$#,##0.00',
                            'A'.$cont.':B'.$cont => 'dd-mm-yyyy'
                        ));

                        $sheet->row($cont, [
                            $detalle->fecha_fg, 
                            '', 
                            'Se entrego el Fondo de garantia del contrato '.$detalle->clave, 
                            '',
                            '',

                            $detalle->monto_fg
                        ]);	
                        $cont++;
                        
                    }
                

                   // $sheet->setBorder('A'.$ini.':I'.$cont, 'thin');

                }

                    
                
                

            
            });
        })->download('xls');
    }

    public function getAnticipos($aviso){
        $anticipos = Anticipo_estimacion::join('ini_obras','anticipos_estimaciones.aviso_id','=','ini_obras.id')
        ->select('anticipos_estimaciones.id','anticipos_estimaciones.fecha_anticipo','anticipos_estimaciones.monto_anticipo','ini_obras.clave')
            ->whereIn('aviso_id',$aviso)
            ->orderBy('aviso_id','asc')
            ->orderBy('fecha_anticipo','asc')->get();
        
        return $anticipos;
    }

    public function getFG($aviso){
        $fondos = Fg_estimacion::join('ini_obras','fg_estimaciones.aviso_id','=','ini_obras.id')
            ->select('fg_estimaciones.id','fg_estimaciones.cantidad','fg_estimaciones.monto_fg','fg_estimaciones.fecha_fg','ini_obras.clave')
            ->whereIn('aviso_id',$aviso)
            ->orderBy('aviso_id','asc')
            ->orderBy('id','asc')->get();
        
        return $fondos;
    }

    public function getContratos($contratista, $fraccionamiento, $constructora){
        return $iniObras = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave',
                'ini_obras.total_costo_directo',
                'ini_obras.total_costo_indirecto', 
                'ini_obras.total_importe',
                'ini_obras.anticipo',
                'ini_obras.garantia_ret',
                'ini_obras.porc_garantia_ret',
                'ini_obras.num_casas',
                'ini_obras.emp_constructora',
                'ini_obras.emp_constructora',
                'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto')
            ->where('ini_obras.num_casas','!=', 0)
            ->where('ini_obras.fraccionamiento_id','=',$fraccionamiento)
            ->where('ini_obras.emp_constructora','=',$constructora)
            ->where('ini_obras.contratista_id','=',$contratista)->get();
    }

    public function calculos($clave){
        $est = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                                ->select('num_estimacion','total_estimacion')
                                ->where('estimaciones.aviso_id','=',$clave)
                                ->orderBy('num_estimacion','desc')->distinct()->get();
        if(sizeof($est) == 0){
            $total_estimacion = 0;
            $num_est = 0;
        }
        else{
            $num_est = $est[0]->num_estimacion;
            $total_estimacion = $est[0]->total_estimacion;
        }

        $acumAntTotal = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
        ->select(
            'total_estimacion'
        )
        ->where('estimaciones.aviso_id','=',$clave)
        ->where('num_estimacion','<',$num_est)
        ->distinct('total_estimacion')
        ->get();

        $totalEstimacionAnt = 0;

        if(sizeof($acumAntTotal)){
            foreach($acumAntTotal as $index => $acum){
                $totalEstimacionAnt += $acum->total_estimacion;
            }
        }

        return ['totalAct'=> $total_estimacion,
                'totalAnt'=>$totalEstimacionAnt,
                'num' => $num_est];
    }
 
}
