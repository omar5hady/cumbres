<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ini_obra;
use App\Ini_obra_lote;
use App\Lote;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use NumerosEnLetras;
use Excel;
use PHPExcel_Worksheet_Drawing;
use App\Credito;

class IniObraController extends Controller
{
 
    
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto')
            ->orderBy('ini_obras.id', 'desc')->paginate(8);
        }
        else{
            if($criterio!='ini_obras.f_ini' && $criterio!='ini_obras.f_fin'){
                $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
                ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
                'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
                'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto')
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->orderBy('ini_obras.id', 'desc')->paginate(8);
            }
            else{
                $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
                ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
                'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
                'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto')
                ->where($criterio, '=',  $buscar )
                ->orderBy('ini_obras.id', 'desc')->paginate(8);
            }

          
        }
         
        return [
            'pagination' => [
                'total'        => $ini_obra->total(),
                'current_page' => $ini_obra->currentPage(),
                'per_page'     => $ini_obra->perPage(),
                'last_page'    => $ini_obra->lastPage(),
                'from'         => $ini_obra->firstItem(),
                'to'           => $ini_obra->lastItem(),
            ],
            'ini_obra' => $ini_obra
        ];
    }
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto','ini_obras.anticipo',
            'ini_obras.total_anticipo','ini_obras.costo_indirecto_porcentaje','ini_obras.fraccionamiento_id',
            'ini_obras.contratista_id','ini_obras.descripcion_corta','ini_obras.descripcion_larga',
            'ini_obras.iva','ini_obras.tipo','ini_obras.total_superficie')
        ->where('ini_obras.id','=',$id)
        ->orderBy('ini_obras.id', 'desc')->take(1)->get();
         
        return ['ini_obra' => $ini_obra];
    }
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $detalles = Ini_obra_lote::select('ini_obra_lotes.costo_directo',
        'ini_obra_lotes.costo_indirecto','ini_obra_lotes.importe','ini_obra_lotes.lote',
        'ini_obra_lotes.manzana','ini_obra_lotes.modelo','ini_obra_lotes.construccion',
        'ini_obra_lotes.descripcion','ini_obra_lotes.id','ini_obra_lotes.ini_obra_id',
        'ini_obra_lotes.lote_id','ini_obra_lotes.obra_extra')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.lote', 'desc')->get();
         
        return ['detalles' => $detalles];
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fecha_ini = $request->f_ini;
        $fecha_fin = $request->f_fin;
 
        try{
            DB::beginTransaction(); 
            $ini_obra = new Ini_obra();
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $fecha_ini;
            $ini_obra->f_fin = $fecha_fin;
            $ini_obra->total_importe = $request->total_importe;
            $ini_obra->total_costo_directo = $request->total_costo_directo;
            $ini_obra->total_costo_indirecto =  $request->total_costo_indirecto;
            $ini_obra->anticipo = $request->anticipo;
            $ini_obra->total_anticipo = $request->total_anticipo;
            $ini_obra->costo_indirecto_porcentaje = $request->costo_indirecto_porcentaje;
            $ini_obra->descripcion_larga = $request->descripcion_larga;
            $ini_obra->descripcion_corta = $request->descripcion_corta;
            $ini_obra->tipo = $request->tipo;
            $ini_obra->iva = $request->iva;
            $ini_obra->total_superficie = $request->total_superficie;
            $ini_obra->save();
 
            $lotes = $request->data;//Array de detalles
            //Recorro todos los elementos
 
            foreach($lotes as $ep=>$det)
            {
                $lotes = new Ini_obra_lote();
                $lotes->ini_obra_id = $ini_obra->id;
                $lotes->lote = $det['lote'];
                $lotes->manzana = $det['manzana'];
                $lotes->modelo = $det['modelo'];
                $lotes->construccion = $det['superficie'];
                $lotes->costo_directo = $det['costo_directo'];
                $lotes->costo_indirecto = $det['costo_indirecto'];
                $lotes->importe = $det['importe'];       
                $lotes->descripcion = $det['descripcion'];
                $lotes->lote_id= $det['lote_id'];
                $lotes->obra_extra= $det['obra_extra'];
                $lotes->save();

                if($det['lote_id']>0){
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
                    $lote->fecha_ini = $fecha_ini;
                    $lote->fecha_fin = $fecha_fin;
                    $lote->obra_extra=$det['obra_extra'];
                    if($lote->contrato==0){
                        

                        $credito_id = Credito::select('id','precio_obra_extra','precio_venta')->where('lote_id','=',$det['lote_id'])
                        ->where('contrato','=',0)->get();

                        foreach($credito_id as $creditoid){
                            $newPrecioVenta = $creditoid->precio_venta - $creditoid->precio_obra_extra + $det['obra_extra'];
                            $credito = Credito::findOrFail($creditoid->id);
                            $credito->precio_venta=$newPrecioVenta;
                            $credito->precio_obra_extra =  $det['obra_extra'];
                            $credito->save();
                        }
                    }
                    $lote->save();
                }
            }          
 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function select_manzana_lotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $lotesManzana = Lote::select('manzana')
                        ->where('fraccionamiento_id','=',$buscar)
                        ->where('ini_obra', '=', '1')
                        ->where('aviso','=','0')->distinct()
                        ->get();

                        return ['lotesManzana' => $lotesManzana];
    }

    public function select_numContrato (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $contratos = Ini_obra::select('clave')
                        ->where('fraccionamiento_id','=',$buscar)
                        ->get();

                        return ['numContratos' => $contratos];
    }

    public function select_lotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $lotes = Lote::select('num_lote','id','fecha_fin')
                        ->where('fraccionamiento_id','=',$buscar2)
                        ->where('manzana','=',$buscar)
                        ->where('ini_obra', '=', '1')
                        ->where('aviso','=','0')
                        ->get();

                        return ['lotes' => $lotes];
    }

    public function select_datos_lotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $lotesDatos = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
        ->select('lotes.num_lote as num_lote','lotes.construccion as construccion','lotes.manzana as manzana','modelos.nombre as modelo','lotes.id as lote_id')
                        ->where('lotes.id','=',$buscar)
                        ->where('lotes.ini_obra', '=', '1')
                        ->where('lotes.aviso','=','0')
                        ->get();

                        return ['lotesDatos' => $lotesDatos];
    }

     public function eliminarContrato(Request $request){
        if(!$request->ajax())return redirect('/');
        $lotes = Ini_obra_lote::select('lote_id')
                                ->where('ini_obra_id','=',$request->id)->get();
        foreach($lotes as $ep=>$det){
            if($det->lote_id > 0){
                $lote = Lote::findOrFail($det->lote_id);
                $lote->aviso = '0';
                $lote->save();
            }
        }

        $ini_obra = Ini_obra::findOrFail($request->id);
        $ini_obra->delete();
     }


    public function ActualizarIniObra(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fecha_ini = $request->f_ini;
        $fecha_fin = $request->f_fin;

        try{
            DB::beginTransaction(); 
            $ini_obra = Ini_obra::findOrFail($request->id);
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $fecha_ini;
            $ini_obra->f_fin = $fecha_fin;
            $ini_obra->total_importe = $request->total_importe;
            $ini_obra->total_costo_directo = $request->total_costo_directo;
            $ini_obra->total_costo_indirecto =  $request->total_costo_indirecto;
            $ini_obra->anticipo = $request->anticipo;
            $ini_obra->total_anticipo = $request->total_anticipo;
            $ini_obra->costo_indirecto_porcentaje = $request->costo_indirecto_porcentaje;
            $ini_obra->descripcion_larga = $request->descripcion_larga;
            $ini_obra->descripcion_corta = $request->descripcion_corta;
            $ini_obra->tipo = $request->tipo;
            $ini_obra->iva = $request->iva;
            $ini_obra->total_superficie = $request->total_superficie;
            $ini_obra->save();

            $lotes = $request->data;//Array de detalles
            //Recorro todos los elementos

            foreach($lotes as $ep=>$det)
            {
                $lotes = Ini_obra_lote::findOrFail($det['id']);
                $lotes->ini_obra_id = $ini_obra->id;
                $lotes->lote = $det['lote'];
                $lotes->manzana = $det['manzana'];
                $lotes->modelo = $det['modelo'];
                $lotes->construccion = $det['construccion'];
                $lotes->costo_directo = $det['costo_directo'];
                $lotes->costo_indirecto = $det['costo_indirecto'];
                $lotes->importe = $det['importe'];       
                $lotes->descripcion = $det['descripcion'];
                $lotes->lote_id= $det['lote_id'];
                $lotes->obra_extra= $det['obra_extra'];
                $lotes->save();

                if($det['lote_id']>0){
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
                    $lote->fecha_ini = $fecha_ini;
                    $lote->fecha_fin = $fecha_fin;
                    $lote->obra_extra=$det['obra_extra'];
                    if($lote->contrato==0){
                        

                        $credito_id = Credito::select('id','precio_obra_extra','precio_venta')->where('lote_id','=',$det['lote_id'])
                        ->where('contrato','=',0)->get();

                        foreach($credito_id as $creditoid){
                            $newPrecioVenta = $creditoid->precio_venta - $creditoid->precio_obra_extra + $det['obra_extra'];
                            $credito = Credito::findOrFail($creditoid->id);
                            $credito->precio_venta=$newPrecioVenta;
                            $credito->precio_obra_extra =  $det['obra_extra'];
                            $credito->save();
                        }
                    }
                    $lote->save();
                }
            }          

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }

    }

    public function eliminarIniObraLotes(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $loteIni = Ini_obra_lote::findOrFail($request->id);
            
            if($loteIni->lote_id==0){
                
                $loteIni->delete();
            }else{

            $lote = Lote::findOrFail($loteIni->lote_id);
            $lote->aviso = '0';
            $lote->save();
            $loteIni->delete();
            }

    }

    public function agregarIniObraLotes(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $lotes = new Ini_obra_lote();
        $lotes->ini_obra_id = $request->id;
        $lotes->lote = $request->lote;
        $lotes->manzana = $request->manzana;
        $lotes->modelo = $request->modelo;
        $lotes->construccion = $request->superficie;
        $lotes->costo_directo = $request->costo_directo;
        $lotes->costo_indirecto = $request->costo_indirecto;
        $lotes->importe = $request->importe;       
        $lotes->descripcion = $request->descripcion;
        $lotes->lote_id= $request->lote_id;
        $lotes->save();

    }

    public function contratoObraPDF(Request $request, $id)
    {
        if(!$request->ajax())return redirect('/');
        
        $cabecera = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'contratistas.nombre as contratista','contratistas.rfc as rfc','contratistas.telefono as telefono',
            'contratistas.direccion as direccion','contratistas.colonia as colonia',
            'contratistas.cp as codigoPostal','contratistas.IMSS as imss','contratistas.estado as estado',
            'contratistas.representante as representante','fraccionamientos.nombre as proyecto',
            'fraccionamientos.calle as calleFracc','fraccionamientos.colonia as coloniaFracc',
            'fraccionamientos.estado as estadoFracc','ini_obras.anticipo',
            'ini_obras.total_anticipo','ini_obras.costo_indirecto_porcentaje','ini_obras.fraccionamiento_id',
            'ini_obras.contratista_id','ini_obras.descripcion_corta','ini_obras.descripcion_larga','ini_obras.iva','ini_obras.tipo')
        ->where('ini_obras.id','=',$id)
        ->orderBy('ini_obras.id', 'desc')->take(1)->get();

        setlocale(LC_TIME, 'es_MX.utf8');
        $tiempo = new Carbon($cabecera[0]->f_ini);
        $cabecera[0]->f_ini = $tiempo->formatLocalized('%d de %B de %Y');

        $tiempo2 = new Carbon($cabecera[0]->f_fin);
        $cabecera[0]->f_fin = $tiempo2->formatLocalized('%d de %B de %Y');

        $cabecera[0]->f_ini2 = $tiempo->formatLocalized('%d dias del mes de %B del año %Y');

        $cabecera[0]->total_anticipo = number_format((float)$cabecera[0]->total_anticipo,2,'.','');
        $cabecera[0]->total_costo_directo = number_format((float)$cabecera[0]->total_costo_directo,2,'.','');
        $cabecera[0]->total_costo_indirecto = number_format((float)$cabecera[0]->total_costo_indirecto,2,'.','');
        $cabecera[0]->total_importe = number_format((float)$cabecera[0]->total_importe,2,'.','');

        $cabecera[0]->anticipoLetra = NumerosEnLetras::convertir($cabecera[0]->total_anticipo,'Pesos',false,'Centavos');
        $cabecera[0]->totalImporteLetra = NumerosEnLetras::convertir($cabecera[0]->total_importe,'Pesos',false,'Centavos');

            $pdf = \PDF::loadview('pdf.contratoContratista',['cabecera' => $cabecera]);
            return $pdf->download('contrato.pdf');
            // return ['cabecera' => $cabecera];
    }

    public function exportExcel(Request $request, $id)
    {
        if(!$request->ajax())return redirect('/');
        //Codigo para exportar vista PRE a excell
        $id = $request->id;
        $detalles = Ini_obra_lote::select('ini_obra_lotes.costo_directo',
        'ini_obra_lotes.costo_indirecto','ini_obra_lotes.importe','ini_obra_lotes.lote',
        'ini_obra_lotes.manzana','ini_obra_lotes.modelo','ini_obra_lotes.construccion',
        'ini_obra_lotes.descripcion','ini_obra_lotes.id','ini_obra_lotes.ini_obra_id',
        'ini_obra_lotes.lote_id','ini_obra_lotes.obra_extra')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.lote', 'desc')->get();

        $relacion =  Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'contratistas.nombre as contratista','contratistas.rfc as rfc','contratistas.telefono as telefono',
            'contratistas.direccion as direccion','contratistas.colonia as colonia',
            'contratistas.cp as codigoPostal','contratistas.IMSS as imss','contratistas.estado as estado',
            'contratistas.representante as representante','fraccionamientos.nombre as proyecto',
            'fraccionamientos.calle as calleFracc','fraccionamientos.colonia as coloniaFracc',
            'fraccionamientos.estado as estadoFracc','ini_obras.anticipo','fraccionamientos.cp as cpFracc',
            'ini_obras.total_anticipo','ini_obras.costo_indirecto_porcentaje','ini_obras.fraccionamiento_id',
            'ini_obras.contratista_id','ini_obras.descripcion_corta','ini_obras.descripcion_larga','ini_obras.iva','ini_obras.tipo')
        ->where('ini_obras.id','=',$id)
        ->orderBy('ini_obras.id', 'desc')->take(1)->get();


        return Excel::create('Pre_'.$relacion[0]->proyecto , function($excel) use ($relacion, $detalles){
        $excel->sheet($relacion[0]->clave, function($sheet) use ($relacion, $detalles){
            
            $sheet->mergeCells('A1:G1');
            $sheet->mergeCells('A3:G3');
            $sheet->mergeCells('A5:G5');
            $sheet->setSize('A1', 100, 50);
            $sheet->setSize('E7', 20, 20);
            $sheet->setSize('F7', 20, 20);
            $sheet->setSize('G7', 20, 20);

            $objDrawing = new PHPExcel_Worksheet_Drawing;
            $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($sheet);


            $sheet->cell('A1', function($cell) {

                // manipulate the cell
                $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                $cell->setFontFamily('Arial Narrow');
                $cell->setFontSize(32);
                $cell->setFontWeight('bold');
                $cell->setAlignment('center');
            
            });
            
            
            $sheet->row(3, [
                'Relacion de viviendas en '.'"'.$relacion[0]->proyecto.'"'
            ]);

            $sheet->cell('A3', function($cell) {

                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setFontSize(18);
                $cell->setFontWeight('bold');
                $cell->setAlignment('center');
            
            });


            $sheet->row(5, [
                $relacion[0]->contratista
            ]);

            
            $sheet->cell('A5', function($cell) {

                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setFontSize(14);
                $cell->setFontWeight('bold');
                $cell->setAlignment('center');
            
            });


            $sheet->row(7, [
                'Descripcion', 'manzana', 'lote', 'M2', 'Costo directo', 'Indirectos',
                'Importe'
            ]);


            $sheet->cells('A7:G7', function ($cells) {
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

            
            $cont=7;

            $sheet->setColumnFormat(array(
                'G' => '$#,##0.00',
                'E' => '$#,##0.00',
                'F' => '$#,##0.00'
            ));
            
            foreach($detalles as $index => $detalle) {
                $cont++;       
                
                $sheet->row($index+8, [
                    $detalle->descripcion, 
                    $detalle->manzana, 
                    $detalle->lote, 
                    $detalle->construccion, 
                    $detalle->costo_directo,
                    $detalle->costo_indirecto,
                    $detalle->importe,
                ]);	
                
                
            
            }
    
                $contador = $cont+1;
                $contador0 = $contador+2;
                $contador01 = $contador+3;
                $contador1 = $cont+6;
                $contador2 = $cont+7;
                $contador3 = $cont+8;
                $contador4 = $cont+9;

                $contador5 = $contador4+5;
                $contador6 = $contador5+1;
                $contador7 = $contador6+1;

                $sheet->cells('E'.$contador.':'.'G'.$contador, function ($cells) {
                // Set font weight to bold
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
            });
            

            $sheet->setCellValue('G'.$contador, $relacion[0]->total_importe); 
            $sheet->setCellValue('F'.$contador, $relacion[0]->total_costo_indirecto); 
            $sheet->setCellValue('E'.$contador, $relacion[0]->total_costo_directo); 

            $sheet->setColumnFormat(array(
                'G'.$contador0 => '0.00',
            ));

            $sheet->cell('G'.$contador01, function ($cell) {
                // Set font weight to bold
                
                $cell->setAlignment('left');
            });
            

            $sheet->setCellValue('F'.$contador0, 'Anticipo');
            $sheet->setCellValue('F'.$contador01, 'Total anticipo');
            $sheet->setCellValue('G'.$contador0, $relacion[0]->anticipo.'%');
            $sheet->setCellValue('G'.$contador01, $relacion[0]->total_anticipo);

            $sheet->cell('A'.$contador1, function($cell) {
                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setAlignment('right');
            
            });
            $sheet->cell('A'.$contador2, function($cell) {
                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setAlignment('right');
            
            });
            $sheet->cell('A'.$contador3, function($cell) {
                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setAlignment('right');
            
            });
            $sheet->cell('A'.$contador4, function($cell) {
                // manipulate the cell
                $cell->setFontFamily('Arial Narrow');
                $cell->setAlignment('right');
            
            });
                
                $sheet->setCellValue('A'.$contador1,'Fecha de inicio de obra '); 
                $sheet->setCellValue('A'.$contador2,'Fecha de termino de obra '); 
                $sheet->setCellValue('A'.$contador3,'Clave '); 
                $sheet->setCellValue('A'.$contador4,'Contratista'); 

                $sheet->setCellValue('C'.$contador1,$relacion[0]->f_ini); 
                $sheet->setCellValue('C'.$contador2,$relacion[0]->f_fin); 
                $sheet->setCellValue('C'.$contador3,$relacion[0]->clave); 
                $sheet->setCellValue('C'.$contador4,$relacion[0]->contratista); 

                $sheet->cell('A'.$contador5, function($cell) {
                    // manipulate the cell
                    $cell->setFontColor('#ff0000');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setAlignment('center');
                
                });
            
                $sheet->setCellValue('A'.$contador5,'Datos de la obra'); 
                $sheet->setCellValue('A'.$contador6,$relacion[0]->calleFracc); 
                $sheet->setCellValue('A'.$contador7,$relacion[0]->coloniaFracc . ' C.P. '.$relacion[0]->cpFracc); 
            
            

            $num='A7:G' . $cont;
            $sheet->setBorder($num, 'thin');
        });
        }
        
        )->download('xls');
    
    }

}
