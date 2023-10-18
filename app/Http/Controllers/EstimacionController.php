<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use PHPExcel_Worksheet_Drawing;
use Auth;
use App\Ini_obra;
use App\Ini_obra_lote;
use App\Lote;
use App\Estimacion;
use App\Concepto_extra;
use App\Fg_estimacion;
use App\Anticipo_estimacion;
use App\Hist_estimacion;
use App\Obs_estimacion;
use App\Http\Controllers\IniObraController;
use Carbon\Carbon;

class EstimacionController extends Controller
{
    // Función para generar el resumen de estimaciones por contratista, proyecto y empresa constructora.
    public function resumen(Request $request){
        //$obraC = new IniObraController();
        $fraccionamiento = $request->fraccionamiento;
        $contratista = $request->contratista;
        $constructora = $request->constructora;

        if($request->etapa != ''){
            $etapas = Ini_obra_lote::join('lotes','ini_obra_lotes.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->select('ini_obra_lotes.ini_obra_id')
                        ->where('lotes.etapa_id','=',$request->etapa)
                        ->get();

            $arrayId = [];

            foreach($etapas as $index => $etapa){
                array_push($arrayId,$etapa->ini_obra_id);
            }
        }

        // Query para obtner los folios de los contratos de obra segun criterios de busqueda.
        $iniObras = Ini_obra::select('id')
            ->where('fraccionamiento_id','=',$fraccionamiento)
            ->where('emp_constructora','=',$constructora)
            ->where('contratista_id','=',$contratista);
            if($request->etapa != '')
                $iniObras = $iniObras->whereIn('id',$arrayId);
            $iniObras = $iniObras->get();

        // LLamada a la funcion que retorna la informacion de los contratos de obra
        $contratos = $this->getContratos($contratista, $fraccionamiento, $constructora, $iniObras);
        // LLamada a la funcion que retorna los Fondos de Garantia para todos los contratos encontrados.
        $fondoGarantia = $this->getFG($iniObras);

        $titulo = 'Resumen de estimaciones';

        if(sizeof($contratos)){
            // Se recorre cada contrato.
            foreach ($contratos as $index => $contrato) {
                $contrato->porcAnticipo = 0;
                //Llamda a la funcion que retorna el historial de estimaciones por contrato
                $anterior = $this->calculos($contrato->id);
                $contrato->estimadoAnt = $anterior['totalAnt']; // Total de estimaciones anteriores a la actual
                $contrato->estimadoAct = $anterior['totalAct']; // Total estimación actual
                $contrato->numEst = $anterior['num']; //Numero de estimaciones


                // LLamada a la funcion que retorna los Anticipos para todos los contratos encontrados.
                $contrato->anticipos = $this->getAnticipos($contrato->id);
                if(sizeof($contrato->anticipos)){
                    $sumaAnticipo = 0;
                    foreach ($contrato->anticipos as $index => $anticipo){
                        $sumaAnticipo+=$anticipo->monto_anticipo;
                    }
                    if($sumaAnticipo > 0)
                        $contrato->porcAnticipo = round(($sumaAnticipo/$contrato->total_importe)*100,3);
                }
                // LLamada a la funcion que retorna los Conceptos Extra para todos los contratos encontrados.
                $contrato->conceptosExtra = $this->getConceptosExtra($contrato->id);
            }
        }

        if(sizeof($contratos) == 0)
            return redirect('/');

        // Creación y retorno en excel.
        return Excel::create($titulo , function($excel) use ($fondoGarantia, $contratos){
            $excel->sheet('Resumen', function($sheet) use ($fondoGarantia, $contratos){

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
                    $sheet->cells('A1:A2', function($cell) {
                        // manipulate the cell
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(11);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');

                    });
                    $sheet->cells('A2:A3', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'CONCRETANIA, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(16);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');

                    });

                $sheet->row(1, [
                    'Periodo: '.$contratos[0]->fecha_ini.' - '.$contratos[0]->fecha_fin
                ]);

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

                $suma14 = $suma15 = $suma16 = $suma17 = 0;

                //////////////////// VIVIENDAS ///////////////////////
                    foreach($contratos as $index => $detalle) {

                        $amortAnt = $detalle->estimadoAnt*($detalle->anticipo/100);
                        $fondoG = $detalle->estimadoAnt*($detalle->porc_garantia_ret/100);
                        $netoPagado = $detalle->estimadoAnt - ($amortAnt + $fondoG);

                        $amortAct = $detalle->estimadoAct*($detalle->anticipo/100);
                        $fondoGAct = $detalle->estimadoAct*($detalle->porc_garantia_ret/100);
                        $netoAct = $detalle->estimadoAct - ($amortAct + $fondoGAct);

                        $importeAcum = $detalle->estimadoAnt + $detalle->estimadoAct;
                        $amortAcum = $amortAnt + $amortAct;
                        $fgAcum = $fondoG + $fondoGAct;
                        $netoAcum =$importeAcum - ($fgAcum + $amortAcum);
                        $porEstimar = $detalle->total_importe - $importeAcum;

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
                        $suma16+=$porEstimar;

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
                            $porEstimar
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
                        $suma16
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

                foreach($contratos as $index => $contrato) {
                    $suma14 = 0;
                    $suma17 = 0;
                    if(sizeOf($contrato->anticipos)){
                        $cont++;
                        $ini = $cont;
                        $sheet->mergeCells('A'.$cont.':B'.$cont);
                        $sheet->mergeCells('C'.$cont.':E'.$cont);
                        $sheet->mergeCells('F'.$cont.':G'.$cont);
                        $sheet->cells('A'.$cont.':H'.$cont, function($cell) {

                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');

                        });

                        $sheet->row($cont, [
                            'ANTICIPOS: '.$contrato->clave,
                            '',
                            'MONTO DE ANTICIPO',
                            '',
                            '',
                            'FECHA PAGO DEL ANTICIPO',
                            '',
                            $contrato->porcAnticipo.'%'
                        ]);
                        $ant = '';
                        $cont++;

                        foreach($contrato->anticipos as $index => $detalle) {
                            $suma14 += $detalle->monto_anticipo;

                            $sheet->mergeCells('C'.$cont.':E'.$cont);
                            $sheet->mergeCells('F'.$cont.':G'.$cont);

                            $sheet->setColumnFormat(array(
                                'C'.$cont.':E'.$cont => '$#,##0.00',
                                'F'.$cont.':G'.$cont => 'dd-mm-yyyy'
                            ));

                            $sheet->row($cont, [
                                '',
                                '',
                                $detalle->monto_anticipo,
                                '',
                                '',
                                $detalle->fecha_anticipo
                            ]);
                            $cont++;
                        }

                        $sheet->row($cont, [
                            '', '', $suma14, '','','','',''
                        ]);

                        $sheet->mergeCells('H'.$cont.':J'.$cont);

                        $sheet->setColumnFormat(array(
                            'C'.$cont.':E'.$cont => '$#,##0.00',
                        ));
                        $sheet->mergeCells('C'.$cont.':E'.$cont);

                        $sheet->cells('C'.$cont.':E'.$cont, function($cell) {
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        });

                        $sheet->setBorder('A'.$ini.':H'.$cont, 'thin');

                    }

                    if(sizeOf($contrato->conceptosExtra)){
                        //////// OBRA EXTRA

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
                            'OBRA EXTRA'.$contrato->clave,
                            '',
                            'MONTO DE OBRA EXTRA',
                            '',
                            '',
                            'FECHA',
                            $contrato->porcAnticipo.'%'
                        ]);
                        $ant = '';
                        $cont++;

                        foreach($contrato->conceptosExtra as $index => $detalle) {
                            $suma17 += $detalle->importe;

                            $sheet->mergeCells('C'.$cont.':E'.$cont);
                            $sheet->mergeCells('F'.$cont.':G'.$cont);

                            $sheet->setColumnFormat(array(
                                'C'.$cont.':E'.$cont => '$#,##0.00',
                                'F'.$cont.':G'.$cont => 'dd-mm-yyyy'
                            ));

                            $sheet->row($cont, [
                                '',
                                '',
                                $detalle->importe,
                                '',
                                '',
                                $detalle->fecha
                            ]);
                            $cont++;
                        }

                        $sheet->row($cont, [
                            '', '', $suma17, '','','','',''
                        ]);

                        $sheet->mergeCells('H'.$cont.':J'.$cont);

                        $sheet->setColumnFormat(array(
                            'C'.$cont.':E'.$cont => '$#,##0.00',
                        ));
                        $sheet->mergeCells('C'.$cont.':E'.$cont);

                        $sheet->cells('C'.$cont.':E'.$cont, function($cell) {
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');
                        });

                        $sheet->setBorder('A'.$ini.':H'.$cont, 'thin');
                    }
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

    // Función que retorna los anticipos por contrato
    public function getAnticipos($aviso){
        $anticipos = Anticipo_estimacion::join('ini_obras','anticipos_estimaciones.aviso_id','=','ini_obras.id')
        ->select('anticipos_estimaciones.id','anticipos_estimaciones.fecha_anticipo','anticipos_estimaciones.monto_anticipo','ini_obras.clave')
            ->where('aviso_id','=',$aviso)
            ->orderBy('aviso_id','asc')
            ->orderBy('fecha_anticipo','asc')->get();

        return $anticipos;
    }

    // Función que retorna los conceptos extra por contrato
    public function getConceptosExtra($aviso){
        $conceptos = Concepto_extra::where('aviso_id','=',$aviso)
            ->orderBy('aviso_id','asc')
            ->orderBy('fecha','asc')->get();

        return $conceptos;
    }

    // Función que retorna los fondos de garantia por contrato
    public function getFG($aviso){
        $fondos = Fg_estimacion::join('ini_obras','fg_estimaciones.aviso_id','=','ini_obras.id')
            ->select('fg_estimaciones.id','fg_estimaciones.cantidad','fg_estimaciones.monto_fg','fg_estimaciones.fecha_fg','ini_obras.clave')
            ->whereIn('aviso_id',$aviso)
            ->orderBy('aviso_id','asc')
            ->orderBy('id','asc')->get();

        return $fondos;
    }

    // Función que retorna la informacion de los contratos de obra
    public function getContratos($contratista, $fraccionamiento, $constructora ,$ids){
        $iniObras = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave',
                'ini_obras.total_costo_directo',
                'ini_obras.total_costo_indirecto',
                'ini_obras.total_importe2 as total_importe',
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
            ->whereIn('ini_obras.id',$ids)
            ->where('ini_obras.contratista_id','=',$contratista)->get();

        if(sizeof($iniObras)){
            foreach ($iniObras as $key => $contrato) {
                $fecha = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                ->select('ini','fin')
                ->whereIn('estimaciones.aviso_id',$ids)
                ->where('ini','!=',NULL)
                ->orderBy('fin','desc')->first();

                $contrato->fecha_ini = $fecha->ini;
                $contrato->fecha_fin = $fecha->fin;
            }
        }

        return $iniObras;
    }

    // Función que retorna los calculas de las estimaciones por contrato
    public function calculos($clave){
        // Numero de estimaciones y el total estimado del contrato actualmente.
        $est = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                                ->select('num_estimacion','total_estimacion')
                                ->where('estimaciones.aviso_id','=',$clave)
                                ->orderBy('num_estimacion','desc')->distinct()->get();
        // en caso de no tener estimaciones, se iniciaizan las variables en 0
        if(sizeof($est) == 0){
            $total_estimacion = 0;
            $num_est = 0;
        }
        else{
            $num_est = $est[0]->num_estimacion;
            $total_estimacion = $est[0]->total_estimacion;
        }

        // Monto total de Estimaciones anteriores
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
                // Se suman cada total registrado.
                $totalEstimacionAnt += $acum->total_estimacion;
            }
        }

        return ['totalAct'=> $total_estimacion,
                'totalAnt'=>$totalEstimacionAnt,
                'num' => $num_est];
    }

    // Función que retorna en excel el estado de cuenta por contratista.
    public function resumenEdoCuenta(Request $request){
        //if (!$request->ajax())

        $obraC = new IniObraController();
        $contratos = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id')
            ->where('ini_obras.num_casas','!=',0)
            ->where('contratistas.id','=', $request->contratista)
            ->whereMonth('ini_obras.f_fin','=',$request->mes)
            ->whereYear('ini_obras.f_fin','=',$request->anio)
            ->orderBy('ini_obras.clave', 'desc')->get();

        if(sizeof($contratos)){
            foreach ($contratos as $key => $contrato) {
                $datos = $obraC->getEdoCuenta($contrato->id);
                $contrato->datos = $datos;
            }

            $excel = Excel::create('Reporte de finalización de obra' , function($excel) use ($contratos){
                for($i=0; $i<sizeof($contratos); $i++){
                    $contrato = $contratos[$i]['datos']['contrato'];
                    $number_est = $contratos[$i]['datos']['num_est'];
                    $anticipos = $contratos[$i]['datos']['anticipos'];
                    $fg = $contratos[$i]['datos']['fg'];
                    $totalAnt  = $contratos[$i]['datos']['totalAnt'];
                    $totalFG = $contratos[$i]['datos']['totalFG'];
                    $totalEst = $contratos[$i]['datos']['totalEst'];
                    $conceptosExtra = $contratos[$i]['datos']['conceptosExtra'];
                    $totalExtra = $contratos[$i]['datos']['totalExtra'];

                    $excel->sheet($contrato[0]->clave, function($sheet) use ($contrato, $number_est, $anticipos, $fg, $totalAnt , $totalFG, $totalEst, $conceptosExtra, $totalExtra){

                        $sheet->mergeCells('A1:J1');
                        $sheet->mergeCells('A3:J3');
                        $sheet->mergeCells('A4:J4');
                        $sheet->mergeCells('A5:J5');

                        $sheet->mergeCells('A6:B6');


                        $sheet->setSize('A1', 20, 60);
                        $sheet->setSize('B1', 20, 60);
                        $sheet->setSize('C1', 20, 20);

                        $sheet->setColumnFormat(array(
                            'C' => '$#,##0.00',
                            'B' => 'dd-mm-yyyy'

                        ));

                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        if($contrato[0]->emp_constructora == 'Grupo Constructor Cumbres')
                            $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
                        if($contrato[0]->emp_constructora == 'CONCRETANIA');
                            $objDrawing->setPath(public_path('img/contratos/logoConcretaniaObra.png')); //your image path
                        $objDrawing->setCoordinates('A2');
                        $objDrawing->setWorksheet($sheet);

                        if($contrato[0]->emp_constructora == 'Grupo Constructor Cumbres')
                            $sheet->cell('A1', function($cell) {

                                // manipulate the cell
                                $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                                $cell->setFontFamily('Arial Narrow');
                                $cell->setFontSize(18);
                                $cell->setFontWeight('bold');
                                $cell->setAlignment('center');

                            });
                        if($contrato[0]->emp_constructora == 'CONCRETANIA');
                            $sheet->cell('A1', function($cell) {

                                // manipulate the cell
                                $cell->setValue(  'CONCRETANIA, SA DE CV');
                                $cell->setFontFamily('Arial Narrow');
                                $cell->setFontSize(18);
                                $cell->setFontWeight('bold');
                                $cell->setAlignment('center');

                            });

                        $sheet->row(3, [
                            'Resumen de estimaciones '.$contrato[0]->nombre.' - '.$contrato[0]->clave
                        ]);
                        $sheet->row(4, [
                            'Contratista: '.$contrato[0]->contratista
                        ]);

                        $sheet->cell('A3', function($cell) {

                            // manipulate the cell
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(14);
                            $cell->setFontWeight('bold');
                            $cell->setAlignment('center');

                        });
                        $sheet->cell('A4', function($cell) {

                            // manipulate the cell
                            $cell->setFontFamily('Arial Narrow');
                            $cell->setFontSize(14);
                        // $cell->setFontWeight('bold');
                            $cell->setAlignment('center');

                        });


                        $sheet->cells('A5:C10', function($cells) {

                            // manipulate the cell
                            $cells->setFontFamily('Arial Narrow');
                            $cells->setFontSize(12);
                            $cells->setFontWeight('bold');
                            $cells->setAlignment('center');

                        });



                        $sheet->row(8, [
                            'Importe total: ', '',$contrato[0]->total_importe
                        ]);

                        $renglon = 10;

                        if(sizeof($anticipos)){
                            $sheet->row($renglon, [
                                'Anticipos: ',
                            ]);

                            $renglon ++;

                            foreach($anticipos as $index => $ant) {

                                $sheet->row($renglon, [
                                    '',
                                    $ant->fecha_anticipo,
                                    $ant->monto_anticipo
                                ]);
                                $renglon++;
                            }

                            $sheet->cells('A'.$renglon.':C'.$renglon, function($cells) {
                                $cells->setFontWeight('bold');
                                $cells->setAlignment('right');
                            });
                            $sheet->row($renglon, [
                                '',
                                'TOTAL:',
                                $totalAnt
                            ]);

                            $renglon+=2;

                        }

                        if(sizeof($anticipos)){
                            $sheet->cell('A'.$renglon, function($cell) {

                                // manipulate the cell
                                $cell->setFontFamily('Arial Narrow');
                                $cell->setFontSize(12);
                                $cell->setFontWeight('bold');
                                $cell->setAlignment('center');

                            });

                            $sheet->row($renglon, [
                                'Fondos de Garantia: ',
                            ]);

                            $renglon++;

                            foreach($fg as $index => $f) {

                                $sheet->row($renglon, [
                                    '',
                                    $f->fecha_fg,
                                    $f->monto_fg
                                ]);
                                $renglon++;
                            }

                            $sheet->cells('A'.$renglon.':C'.$renglon, function($cells) {
                                $cells->setFontWeight('bold');
                                $cells->setAlignment('right');
                            });
                            $sheet->row($renglon, [
                                '',
                                'TOTAL:',
                                $totalFG
                            ]);

                            $renglon+=2;
                        }

                        if(sizeof($number_est)){
                            $sheet->cell('A'.$renglon, function($cell) {

                                // manipulate the cell
                                $cell->setFontFamily('Arial Narrow');
                                $cell->setFontSize(12);
                                $cell->setFontWeight('bold');
                                $cell->setAlignment('center');

                            });

                            $sheet->row($renglon, [
                                'Estimaciones: ',
                            ]);

                            $renglon++;

                            foreach($number_est as $index => $est) {

                                $sheet->row($renglon, [
                                    $est->num_estimacion,
                                    $est->ini,
                                    $est->total_pagado
                                ]);
                                $renglon++;
                            }

                            $sheet->cells('A'.$renglon.':C'.$renglon, function($cells) {
                                $cells->setFontWeight('bold');
                                $cells->setAlignment('right');
                            });
                            $sheet->row($renglon, [
                                '',
                                'TOTAL:',
                                $totalEst
                            ]);

                            $renglon+=2;
                        }

                        $saldo = $totalFG + $totalAnt + $totalEst - $contrato[0]->total_importe;

                        $sheet->mergeCells('A'.$renglon.':B'.$renglon);
                        $sheet->cells('A'.$renglon.':C'.$renglon, function($cells) {
                            $cells->setFontWeight('bold');
                            $cells->setAlignment('right');
                        });

                        $sheet->row($renglon, [
                            'SALDO',
                            '',
                            $saldo

                        ]);

                        if(sizeof($conceptosExtra)){
                            $renglon+=2;
                            $sheet->cell('A'.$renglon, function($cell) {

                                // manipulate the cell
                                $cell->setFontFamily('Arial Narrow');
                                $cell->setFontSize(12);
                                $cell->setFontWeight('bold');
                                $cell->setAlignment('center');

                            });

                            $sheet->row($renglon, [
                                'Obra extra: ',
                            ]);

                            $renglon++;

                            foreach($conceptosExtra as $index => $cext) {

                                $sheet->row($renglon, [
                                    $cext->concepto,
                                    $cext->fecha,
                                    $cext->importe
                                ]);
                                $renglon++;
                            }

                            $sheet->cells('A'.$renglon.':C'.$renglon, function($cells) {
                                $cells->setFontWeight('bold');
                                $cells->setAlignment('right');
                            });
                            $sheet->row($renglon, [
                                '',
                                'TOTAL:',
                                $totalExtra
                            ]);

                            $renglon+=2;
                        }



                    });
                }
            });

            return $excel->download('xls');
        }
        else
            return redirect('/');

    }

    //Funcion para almacenar un comentario a un aviso de obra
    public function storeObs(Request $request){
        $obs = new Obs_estimacion();
        $obs->aviso_id = $request->aviso_id;
        $obs->observacion = $request->observacion;
        $obs->usuario = Auth::user()->usuario;
        $obs->save();
    }

}
