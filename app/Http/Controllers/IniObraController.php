<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ini_obra;
use App\Ini_obra_lote;
use App\Lote;
use App\Licencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use NumerosEnLetras;
use Excel;
use PHPExcel_Worksheet_Drawing;
use App\Credito;
use File;
use Session;
use Auth;
use App\Estimacion;
use App\Obs_estimacion;
use App\Fg_estimacion;
use App\Anticipo_estimacion;
use App\Hist_estimacion;
use App\Concepto_extra;
use App\Importe_extra;

class IniObraController extends Controller
{
    //Función privada que retorna la query para obtener los avisos de obra
    private function getQueryAvisos(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        //Query principal
        $avisos = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto', 'ini_obras.documento','ini_obras.total_importe',
            'ini_obras.total_superficie','ini_obras.emp_constructora', 'ini_obras.calle1', 'ini_obras.calle2', 'ini_obras.registro_obra',
            'ini_obras.direccion_proy',
            'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto');
        if($request->tipo == 'Departamentos')
            $avisos = $avisos->where('ini_obras.tipo','=','Departamentos');
        else
            $avisos = $avisos->where('ini_obras.tipo','!=','Departamentos');
        //Filtro por empresa constructora
        if($request->empresa != '')
            $avisos = $avisos->where('ini_obras.emp_constructora','=',$request->empresa);
        //Filtro de busqueda
        if ($buscar!=''){
            if($criterio!='ini_obras.f_ini' && $criterio!='ini_obras.f_fin'){
                $avisos = $avisos
                ->where($criterio, 'like', '%'. $buscar . '%');
            }
            else{//Fecha de inicio
                $avisos = $avisos
                ->where($criterio, '=',  $buscar );
            }
        }
        return $avisos;
    }

    // Función que retorna los avisos de obra
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //Llamada a la función privada que devuelve la query principal
        $ini_obra = $this->getQueryAvisos($request);
        $ini_obra = $ini_obra->orderBy('ini_obras.id', 'desc')->paginate(8);
        //Retorno de información 
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
    //Función que retorna los avisos de obra en excel
    public function excelAvisos(Request $request)
    {
        //Llamada a la función privada que devuelve la query principal
        $ini_obra = $this->getQueryAvisos($request);
        $ini_obra = $ini_obra->orderBy('ini_obras.id', 'desc')->get();
        //Creación y retorno de excel.
        return Excel::create(
            'Avisos de obra',
            function ($excel) use ($ini_obra) {
                $excel->sheet('Avisos de obra', function ($sheet) use ($ini_obra) {

                    $sheet->row(1, [
                        'Clave', 'Contratista','Fraccionamiento', 'Superficie total', 'Importe Total',
                        'Fecha de inicio', 'Fecha de termino'
                    ]);


                    $sheet->cells('A1:G1', function ($cells) {
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
                        'D' => '#,##0.00',
                        'E' => '$#,##0.00',
                    ));


                    $cont = 1;

                    foreach ($ini_obra as $index => $lote) {
                        $cont++;


                        setlocale(LC_TIME, 'es_MX.utf8');
                        $tiempo = new Carbon($lote->f_ini);
                        $lote->f_ini = $tiempo->formatLocalized('%d de %B de %Y');

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $tiempo = new Carbon($lote->f_fin);
                        $lote->f_fin = $tiempo->formatLocalized('%d de %B de %Y');
                        

                        $sheet->row($index + 2, [
                            $lote->clave,
                            $lote->contratista,
                            $lote->proyecto,
                            $lote->total_superficie,
                            $lote->total_importe,
                            $lote->f_ini,
                            $lote->f_fin
                        ]);
                    }
                    $num = 'A1:G' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');
    }

    //Función que retorna los datos principales de un Aviso de obra
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        //Query principal
        $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin', 'ini_obras.calle1', 'ini_obras.calle2',
            'ini_obras.total_costo_directo','ini_obras.total_costo_indirecto','ini_obras.total_importe',
            'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto','ini_obras.anticipo',
            'ini_obras.total_anticipo','ini_obras.costo_indirecto_porcentaje','ini_obras.fraccionamiento_id',
            'ini_obras.porc_garantia_ret', 'ini_obras.num_torres',
            'ini_obras.contratista_id','ini_obras.descripcion_corta','ini_obras.descripcion_larga', 'ini_obras.direccion_proy',
            'ini_obras.iva','ini_obras.tipo','ini_obras.total_superficie')
        ->where('ini_obras.id','=',$id)
        ->orderBy('ini_obras.id', 'desc')->take(1)->get();
         
        return ['ini_obra' => $ini_obra];
    }

    //Función que retorna el detalle de los lotes que conforman un aviso de obra
    public function obtenerDetalles(Request $request){
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        //Query
        $detalles = Ini_obra_lote::leftJoin('lotes','ini_obra_lotes.lote_id','=','lotes.id')
        ->select('ini_obra_lotes.costo_directo', 'lotes.sublote', 'lotes.fin_obra',
            'ini_obra_lotes.costo_indirecto','ini_obra_lotes.importe','ini_obra_lotes.lote',
            'ini_obra_lotes.manzana','ini_obra_lotes.modelo','ini_obra_lotes.construccion',
            'ini_obra_lotes.descripcion','ini_obra_lotes.id','ini_obra_lotes.ini_obra_id',
            'ini_obra_lotes.lote_id','ini_obra_lotes.obra_extra')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.lote', 'desc')->get();
         
        return ['detalles' => $detalles];
    }
 
    //Función para registrar un nuevo aviso de obra.
    public function store(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        $fecha_ini = $request->f_ini;
        $fecha_fin = $request->f_fin;
        $lotes = $request->data;//Array de detalles
 
        try{
            DB::beginTransaction(); 
            //Se crea el registro para la Tabla ini_obras
            $ini_obra = new Ini_obra();
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $fecha_ini;
            $ini_obra->f_fin = $fecha_fin;
            $ini_obra->calle1 = $request->calle1;
            $ini_obra->calle2 = $request->calle2;
            $ini_obra->total_importe = $request->total_importe;
            $ini_obra->total_costo_directo = $request->total_costo_directo;
            $ini_obra->total_costo_indirecto =  $request->total_costo_indirecto;
            $ini_obra->anticipo = $request->anticipo;
            $ini_obra->total_anticipo = $request->total_anticipo;
            $ini_obra->costo_indirecto_porcentaje = $request->costo_indirecto_porcentaje;
            $ini_obra->descripcion_larga = $request->descripcion_larga;
            $ini_obra->descripcion_corta = $request->descripcion_corta;
            $ini_obra->tipo = $request->tipo;
            if($request->porc_garantia_ret != ''){
                $ini_obra->porc_garantia_ret = $request->porc_garantia_ret;
                $ini_obra->garantia_ret =  $ini_obra->total_importe * ($ini_obra->porc_garantia_ret/100);
            }
            $ini_obra->iva = $request->iva;
            $ini_obra->total_superficie = $request->total_superficie;
            $ini_obra->emp_constructora = $request->emp_constructora;
            $ini_obra->direccion_proy = $request->direccion_proy;
            $ini_obra->num_torres = $request->num_torres;
            $ini_obra->num_casas = sizeof($lotes);
            $ini_obra->save();
            
            
            //Recorro todos los elementos del array
            foreach($lotes as $ep=>$det)
            {
                //Se genera un nuevo registro para cada lote que conforma el aviso.
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
                //Se verifica que la partida corresponda a un lote
                if($det['lote_id']>0){
                    //Se accede al registro en la tabla de lotes
                    //Para actualizar la información correspondiente al contrato de obra
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
                    $lote->fecha_ini = $fecha_ini;
                    $lote->fecha_fin = $fecha_fin;
                    $lote->obra_extra=$det['obra_extra'];
                    $lote->fin_obra = $det['fin_obra'];
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

    //Función que retorna las manzanas correspondientes a lotes con inicio de obra pendiente.
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

    //Función que retorna las claves correspondientes a los contratos de obra por fraccionamiento.
    public function select_numContrato (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $contratos = Ini_obra::select('clave')
                        ->where('fraccionamiento_id','=',$buscar)
                        ->get();

                        return ['numContratos' => $contratos];
    }

    //Función que retorna los lotes con inicio de obra pendiente.
    public function select_lotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $manzana = $request->buscar;
        $proyecto = $request->buscar2;
        $lotes = Lote::select('num_lote','sublote', 'id','fecha_fin','emp_constructora')
                        ->where('fraccionamiento_id','=',$proyecto);
                        if($manzana != '')
                            $lotes = $lotes->where('manzana','=',$manzana);
                        $lotes = $lotes->where('ini_obra', '=', '1')
                        ->where('aviso','=','0')
                        ->get();

                        return ['lotes' => $lotes];
    }

    //Función que retorna los datos de un lote para el aviso de obra.
    public function select_datos_lotes (Request $request)
    {
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $lotesDatos = Lote::join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.num_lote as num_lote','lotes.construccion as construccion',
                    'lotes.manzana as manzana','modelos.nombre as modelo',
                    'lotes.sublote',
                'lotes.id as lote_id','lotes.emp_constructora')
                ->where('lotes.id','=',$buscar)
                ->where('lotes.ini_obra', '=', '1')
                ->where('lotes.aviso','=','0')
                ->get();

                return ['lotesDatos' => $lotesDatos];
    }

    //Función para eliminar un aviso de obra.
    public function eliminarContrato(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        //Se obtienen los lotes que corresponden al aviso de obra
        $lotes = Ini_obra_lote::select('lote_id')
                                ->where('ini_obra_id','=',$request->id)->get();
        //Se recorre el arreglo de lotes 
        foreach($lotes as $ep=>$det){
            if($det->lote_id > 0){
                $lote = Lote::findOrFail($det->lote_id);
                $lote->aviso = '0';//Se indica que el lote no tiene un aviso de obra
                $lote->save();
            }
        }
        //Se elimina el registro del aviso de obra.
        $ini_obra = Ini_obra::findOrFail($request->id);
        $ini_obra->delete();
    }

    // Función para actualizar un aviso de obra.
    public function ActualizarIniObra(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        $fecha_ini = $request->f_ini;
        $fecha_fin = $request->f_fin;
        $lotes = $request->data;//Array de detalles

        try{
            DB::beginTransaction(); 
            //Se accede al registro del aviso de obra.
            $ini_obra = Ini_obra::findOrFail($request->id);
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $fecha_ini;
            $ini_obra->f_fin = $fecha_fin;
            $ini_obra->calle1 = $request->calle1;
            $ini_obra->calle2 = $request->calle2;
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
            $ini_obra->direccion_proy = $request->direccion_proy;
            if($request->porc_garantia_ret != ''){
                $ini_obra->porc_garantia_ret = $request->porc_garantia_ret;
                $ini_obra->garantia_ret =  round($ini_obra->total_importe * ($ini_obra->porc_garantia_ret/100),2);
            }
            $ini_obra->num_torres = $request->num_torres;
            $ini_obra->num_casas = sizeof($lotes);
            $ini_obra->save();

            //Recorro todos los elementos del array
            foreach($lotes as $ep=>$det)
            {
                //Se accede al registro de la partida para el aviso de obra
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
                //Se verifica que la partida corresponda a un lote
                if($det['lote_id']>0){
                    //Se accede al registro del lote y se actualiza información
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
                    $lote->fecha_ini = $fecha_ini;
                    $lote->fecha_fin = $fecha_fin;
                    $lote->obra_extra=$det['obra_extra'];
                    $lote->fin_obra= $det['fin_obra'];
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

    // Función para eliminar una partida del aviso de obra.
    public function eliminarIniObraLotes(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        $loteIni = Ini_obra_lote::findOrFail($request->id);
            //En caso de ser una partida relacionada a un lote
            if($loteIni->lote_id!=0){
                //Se actualiza el lote indicando que no tiene una aviso de obra.
                $lote = Lote::findOrFail($loteIni->lote_id);
                $lote->aviso = '0';
                $lote->save();
            }
            //Se elimina el registro de la partida.
            $loteIni->delete();
    }

    //Función para registrar una nueva partida al aviso de obra.
    public function agregarIniObraLotes(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
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

    //Función para imprimir el contrato de obra en PDF.
    public function contratoObraPDF(Request $request)
    {
        $id = $request->id;
        //Query para obtener los datos del contrato
        $cabecera = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.*',
            'contratistas.nombre as contratista','contratistas.rfc as rfc','contratistas.telefono as telefono',
            'contratistas.direccion as direccion','contratistas.colonia as colonia',
            'contratistas.cp as codigoPostal','contratistas.IMSS as imss','contratistas.estado as estado',
            'contratistas.representante as representante','fraccionamientos.nombre as proyecto',
            'fraccionamientos.calle as calleFracc','fraccionamientos.colonia as coloniaFracc', 'fraccionamientos.delegacion',
            'fraccionamientos.estado as estadoFracc','fraccionamientos.ciudad as ciudadFracc')
        ->where('ini_obras.id','=',$id)
        ->orderBy('ini_obras.id', 'desc')->take(1)->get();
        //Se obtienen los datos relacionados a los lotes del contrato.
        $cabecera[0]->etapa = Ini_obra_lote::join('lotes','ini_obra_lotes.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->select('etapas.num_etapa')
                        ->where('ini_obra_lotes.ini_obra_id','=',$id)
                        ->first();
        //Formato de fecha
        setlocale(LC_TIME, 'es_MX.utf8');
        $tiempo = new Carbon($cabecera[0]->f_ini);
        $cabecera[0]->f_ini = $tiempo->formatLocalized('%d de %B de %Y');
        //Apoderado legal que es seleccionado
        $cabecera[0]->apoderado = $request->apoderado;
        //Formato para fechas
        $tiempo2 = new Carbon($cabecera[0]->f_fin);
        $cabecera[0]->f_fin = $tiempo2->formatLocalized('%d de %B de %Y');
        $cabecera[0]->f_ini2 = $tiempo->formatLocalized('%d dias del mes de %B del año %Y');
        //Formato de moneda
        $cabecera[0]->total_anticipo = number_format((float)$cabecera[0]->total_anticipo,2,'.','');
        $cabecera[0]->total_costo_directo = number_format((float)$cabecera[0]->total_costo_directo,2,'.','');
        $cabecera[0]->total_costo_indirecto = number_format((float)$cabecera[0]->total_costo_indirecto,2,'.','');
        $cabecera[0]->total_importe2 = number_format((float)$cabecera[0]->total_importe,2,'.',',');
        //Alamacenamiento de cantidad en letra
        $cabecera[0]->anticipoLetra = NumerosEnLetras::convertir($cabecera[0]->total_anticipo,'Pesos',true,'Centavos');
        $cabecera[0]->totalImporteLetra = NumerosEnLetras::convertir($cabecera[0]->total_importe,'Pesos',true,'Centavos');
            //Creación de PDF
            $pdf = \PDF::loadview('pdf.contratoContratista',['cabecera' => $cabecera]);
            //retorno de archivo.
            return $pdf->stream('contrato.pdf');
    }

    //Codigo para exportar vista PRE a excel
    public function exportExcel(Request $request, $id)
    {
        $id = $request->id;
        //Query para obtener los lotes o partidas que contiene el aviso de obra
        $detalles = Ini_obra_lote::join('lotes','ini_obra_lotes.lote_id','=','lotes.id')
        ->select('ini_obra_lotes.costo_directo', 'lotes.sublote',
            'ini_obra_lotes.costo_indirecto','ini_obra_lotes.importe','ini_obra_lotes.lote',
            'ini_obra_lotes.manzana','ini_obra_lotes.modelo','ini_obra_lotes.construccion',
            'ini_obra_lotes.descripcion','ini_obra_lotes.id','ini_obra_lotes.ini_obra_id',
            'ini_obra_lotes.lote_id','ini_obra_lotes.obra_extra')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.lote', 'desc')->get();
        //Query para obtener los datos principales del aviso de obra
        $relacion =  Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
        ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
        ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
            'ini_obras.emp_constructora',
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

        //Creación y retorno de Excel
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
            if($relacion[0]->emp_constructora == 'Grupo Constructor Cumbres')
                $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
            if($relacion[0]->emp_constructora == 'CONCRETANIA');
                $objDrawing->setPath(public_path('img/contratos/logoConcretaniaObra.png')); //your image path
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($sheet);

            if($relacion[0]->emp_constructora == 'Grupo Constructor Cumbres')
                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(32);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
            if($relacion[0]->emp_constructora == 'CONCRETANIA');
                $sheet->cell('A1', function($cell) {

                    // manipulate the cell
                    $cell->setValue(  'CONCRETANIA, SA DE CV');
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
                'Descripcion', 'Manzana', 'Lote', 'M2', 'Costo directo', 'Indirectos',
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
                    $detalle->lote.' '.$detalle->sublote, 
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
    ////////////////////////////////////////////////////////////////////////////////
    //Función para registrar y almacenar en el servidor el archivo del contrato.
    public function formSubmitContratoObra(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        //Se accede a la información del aviso de obra
        $pdfAnterior = Ini_obra::select('documento', 'id')
            ->where('id', '=', $id)
            ->get();
        //En caso de tener un archivo ya almacenado
        if ($pdfAnterior->isEmpty() != 1) {
            //Se elimina el archivo anterior
            $pathAnterior = public_path() . '/files/contratos/obra/' . $pdfAnterior[0]->documento;
            File::delete($pathAnterior);
        }
        //Variable que almacena el nombre con el que se guardara el nuevo archivo
        $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
        //Se almacena el archivo en el servidor
        $moved =  $request->pdf->move(public_path('/files/contratos/obra/'), $fileName);
        //Si se almacena correctamente
        if ($moved) {
            //Se registra el nombre del archivo en el registro del aviso de obra.
            $documento = Ini_obra::findOrFail($request->id);
            $documento->documento = $fileName;
            $documento->save(); //Insert

        }

        return back();
    }
    //Función para registrar y almacenar en el servidor el archivo del registro de obra.
    public function formSubmitRegistroObra(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 )return redirect('/');
        //Se accede a la información del aviso de obra
        $pdfAnterior = Ini_obra::select('registro_obra', 'id')
            ->where('id', '=', $id)
            ->get();
        //En caso de tener un archivo ya almacenado
        if ($pdfAnterior->isEmpty() != 1) {
            //Se elimina el archivo anterior
            $pathAnterior = public_path() . '/files/contratos/registro_obra/' . $pdfAnterior[0]->documento;
            File::delete($pathAnterior);
        }
        //Variable que almacena el nombre con el que se guardara el nuevo archivo
        $fileName = time() . '.' . $request->pdf->getClientOriginalExtension();
        //Se almacena el archivo en el servidor
        $moved =  $request->pdf->move(public_path('/files/contratos/registro_obra/'), $fileName);
        //Si se almacena correctamente
        if ($moved) {
            //Se registra el nombre del archivo en el registro del aviso de obra.
            $documento = Ini_obra::findOrFail($request->id);
            $documento->registro_obra = $fileName;
            $documento->save(); //Insert

        }
        return back();
}

    //Función para descargar el archivo correspondiente al contrato de obra
    public function downloadFile($fileName)
    {
        $pathtoFile = public_path() . '/files/contratos/obra/' . $fileName;
        return response()->file($pathtoFile);
    }
    //Función para descargar el archivo correspondiente registro de obra
    public function downloadRegistroObra($fileName)
    {

        $pathtoFile = public_path() . '/files/contratos/registro_obra/' . $fileName;
        return response()->file($pathtoFile);
    }

    //Función que retorna los avisos de obra que no tienen registradas estimaciones.
    public function getSinEstimaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $query = Ini_obra::select('id','clave','num_casas','total_importe','porc_garantia_ret')
        ->where('total_importe2','=',0)
        ->where('clave', 'like', '%'. $request->buscar . '%');
        if($request->tipo != '')
            $query = $query->where('tipo','=',$request->tipo);
        $query = $query->orderBy('clave','asc')
        ->get();

        return['contratos'=>$query];
    }

    //Funcion para registras las partidas para estimaciones desde excel.
    public function import(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //validación de archivo
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            //Se obtiene la extensión del archivo
            $extension = File::extension($request->file->getClientOriginalName());
            //Se verifica que el archivo sea compatible.
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                //Se obtienen la cantidad de lotes correspondientes al aviso de obra
                $lotes = Ini_obra_lote::select('ini_obra_id')
                ->where('ini_obra_id','=',$request->contrato)
                ->where('lote','!=',NULL)->count();   
                //Se actualiza el registro del aviso de obra con los datos capturados
                $contrato = Ini_obra::findOrFail($request->contrato);
                $contrato->num_casas = $lotes;
                $contrato->porc_garantia_ret = $request->porcentaje_garantia;
                $contrato->garantia_ret = $request->garantia_ret;
                $contrato->total_importe2 = $request->total_importe;
                $contrato->save();

                $path = $request->file->getRealPath();
                //Se cargan los datos del archivo excel
                $data = Excel::load($path, function($reader) {
                })->get();

                if(!empty($data) && $data->count()){
                    //Se recorren los datos obtenidos 
                    foreach ($data as $key => $value) {
                        //En un arreglo se van almacenando los datos 
                        $insert[] = [
                            'aviso_id' => $request->contrato,
                            'partida' => $value->partida,
                            'pu_prorrateado' => $value->pu_prorrateado
                        ];
                    }
                    
                    if(!empty($insert)){
                        //Se cargan las partidas para la estimacion
                        $insertData = DB::table('estimaciones')->insert($insert);
                        if ($insertData) {
                            Session::flash('success', 'Your Data has successfully imported');
                        }else {                        
                            Session::flash('error', 'Error inserting the data..');
                            return back();
                        }
                    }
                }
                return back();
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }

    //Función que retorna los contratos de obra que han sido habilitados para estimaciones
    public function indexEstimaciones(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        //Query que retorna los avisos de obra habilitados
        $ini_obra = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.total_importe2 as total_importe', 'ini_obras.garantia_ret',
            'ini_obras.total_anticipo', 'ini_obras.num_casas', 'ini_obras.costo_indirecto_porcentaje',
            'ini_obras.anticipo', 'fraccionamientos.tipo_proyecto',
            'ini_obras.porc_garantia_ret',
            'contratistas.nombre as contratista','fraccionamientos.nombre as proyecto')
            ->where('ini_obras.total_importe2','!=',0)
            ->where('fraccionamientos.tipo_proyecto','=',$request->tipo);
        //Busqueda por proyecto
        if($request->proyecto != '')
            $ini_obra = $ini_obra->where('ini_obras.fraccionamiento_id','=',$request->proyecto);
        
        if($buscar != ''){
            $ini_obra = $ini_obra
            //Busqueda por clave de contrato
            ->where('ini_obras.clave','like','%'.$request->buscar.'%')
            //Busqueda por nombre de contratista
            ->orWhere('contratistas.nombre','like','%'.$request->buscar.'%');
            if($request->proyecto != '')//Busqueda por proyecto para el OrWhere
                $ini_obra = $ini_obra->where('ini_obras.fraccionamiento_id','=',$request->proyecto);
        }

        $ini_obra = $ini_obra
            ->where('ini_obras.num_casas','!=',0)
            ->orderBy('ini_obras.clave', 'desc')->paginate(12);

        if(sizeof($ini_obra))
        //Se recorren los contratos
            foreach($ini_obra as $index => $obra){
                //Se obtiene el monto de anticipo del contrato.
                $anticipoT = Anticipo_estimacion::select(DB::raw("SUM(monto_anticipo) as total"))
                                    ->where('aviso_id','=',$obra->id)->first();
                if($anticipoT->total != null){
                    $obra->anticipo = 0;
                    $obra->total_anticipo = 0;
                    $obra->total_anticipo = $anticipoT->total;
                }
                    

                if($obra->total_importe != 0)
                    $obra->anticipo = round(($obra->total_anticipo/$obra->total_importe)*100,3);
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
            'estimaciones' => $ini_obra
        ];
    }

    //Función privada que retorna los conceptos extra del Aviso de Obra
    private function getConceptosExtra($clave){
        return Concepto_extra::where('aviso_id','=',$clave)->orderBy('fecha','asc')->get();
    }
    //Función privada que retorna las observaciones del Aviso de Obra
    private function getObs($clave){
        return Obs_estimacion::where('aviso_id','=',$clave)->orderBy('created_at','desc')->get();
    }

    //Función privada que retorna los importes extra del Aviso de Obra
    private function getImporteExtra($clave){
        return Importe_extra::where('aviso_id','=',$clave)->orderBy('fechaExtra','desc')->get();
    }

    //Funcion para obtener las partidas de un contrato para modulo de departamentos
    public function getPartidasDep (Request $request) {
        return Estimacion::where('aviso_id','=',$request->clave)->orderBy('id','asc')->get();
    }
    //Función que retorna las partidas de las estimaciones
    public function getNivelesDep(Request $request){
        return Ini_obra_lote::join('lotes','ini_obra_lotes.lote_id','=','lotes.id')
                        ->select('lotes.manzana as nivel')
                        ->where('ini_obra_lotes.ini_obra_id','=',$request->clave)
                        ->orderBy('lotes.manzana','asc')->distinct()->get();
    }

    //Funcion que retorna las partidas de las estimaciones para el aviso de obra
    public function getPartidas(Request $request){
        //Llamada a la función privada que retorna los anticipos
        $anticipos = $this->getAnticipos($request->clave);
        //Llamada a la función privada que retorna los fondos de garantia
        $fondos = $this->getFG($request->clave);
        //Llamada a la función privada que retorna los importes extra
        $importesExtra = $this->getImporteExtra($request->clave);
        //Llamada a la función privada que retorna los conceptos extra
        $conceptosExtra = $this->getConceptosExtra($request->clave);
        //Llamada a la función privada que retorna las observaciones
        $observaciones = $this->getObs($request->clave);
        //Variable para almacenar la fecha de pago de la estimación.
        $fecha_pago = '';

        $aviso = Ini_obra::select('num_casas')->where('id','=',$request->clave)->first();

        $acumAntTotal = [];
        //Query para obtener las partidas de las estimaciones
        $estimaciones = $this->getPartidasDep($request);
        //Query que obtiene del historial de estimaciones el numero de estimaciones que se han creado.
        $act = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                        ->select('num_estimacion')
                        ->where('estimaciones.aviso_id','=',$request->clave)
                        ->orderBy('num_estimacion','desc')->distinct()->get();
        //Query que obtiene todo el historial de estimaciones del aviso de obra
        $est = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                                ->select('num_estimacion','total_estimacion')
                                ->where('estimaciones.aviso_id','=',$request->clave);
        //Filtro para numero de estimacion a buscar
        if($request->numero != ''){
            $est = $est->where('num_estimacion','<=',$request->numero);
        }

        $est = $est->orderBy('num_estimacion','desc')->distinct()->get();
        

        if(sizeof($est) == 0){
            $total_estimacion = 0;
            $num_est = 0;
        }
        else{
            //Se almacena el numero de estimación a mostrar y el monto total
            $num_est = $est[0]->num_estimacion;
            $total_estimacion = $est[0]->total_estimacion;
        }

        //Se obtienen los montos total de las estimaciones anteriores.
        $acumAntTotal = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
            ->select(
                'total_estimacion'
            )
            ->where('estimaciones.aviso_id','=',$request->clave)
            ->where('num_estimacion','<',$num_est)
            ->distinct('total_estimacion')
        ->get();

        $totalEstimacionAnt = 0;

        if(sizeof($acumAntTotal)){
            //Se recorren los resultados obtenidos y se calcula el total
            foreach($acumAntTotal as $index => $acum){
                $totalEstimacionAnt += $acum->total_estimacion;
            }
        }
            
        $num = $num_est + 1;

        if(sizeof($act) == 0)
            $actual = 0;
        else
            $actual = $act[0]->num_estimacion;

        //Se recorren las partidas de las estimaciones
        foreach($estimaciones as $index => $estimacion){
            $estimacion->num_estimacion = 0;
            $estimacion->costo = 0;
            $estimacion->vol = 0;
            $estimacion->acumVol = 0;
            $estimacion->acumCosto = 0;
            $estimacion->porEstimarCosto = 0;
            $estimacion->porEstimarVol = 0;
            $estimacion->costoA = 0;
            $estimacion->num_casas = $aviso->num_casas;

            if($estimacion->cant_tope != 0)
                $estimacion->num_casas = $estimacion->cant_tope;
            // Si hay una estimacion a mostrar
            if($num_est != 0){
                //Se obtienen el total de volumen y costo acumulados
                $acum = Hist_estimacion::select(
                        DB::raw("SUM(vol) as volumen"),
                        DB::raw("SUM(costo) as totalCosto")
                    )
                    ->where('estimacion_id','=',$estimacion->id)
                    ->where('num_estimacion','<=',$num_est)
                    ->get();
                
                //Se asignan los totales a la partida
                if( $acum[0]->volumen > 0 ){
                    $estimacion->acumVol = $acum[0]->volumen;
                    $estimacion->acumCosto = $acum[0]->totalCosto;
                }
                //Se obtiene el vol, costo y el numero actual
                $historial = Hist_estimacion::select(
                    'vol', 'costo', 'num_estimacion', 'fecha_pago'
                )
                ->where('estimacion_id','=',$estimacion->id)
                ->where('num_estimacion','=',$num_est)
                ->get();
                //Se asigna el valor actual en la estimación
                if( sizeOf($historial) > 0 ){
                    $estimacion->vol = $historial[0]->vol;
                    $estimacion->costoA = $historial[0]->costo;
                    $fecha_pago = $historial[0]->fecha_pago;
                }
            }
        }
        //Se obtienen monto total del anticipo capturado
        $anticipoT = Anticipo_estimacion::select(DB::raw("SUM(monto_anticipo) as total"))
            ->where('aviso_id','=',$request->clave)->first();
            $total_anticipo = 0;
            if($anticipoT->total != null)
                $total_anticipo = $anticipoT->total;

            $total_anticipo = $total_anticipo;
            //Se calcula el % de anticipo.
            $anticipo = round(($total_anticipo/$request->total_importe)*100,3);

        return [
            'estimaciones' => $estimaciones, 
            'total_estimacion' => $total_estimacion,
            'num_est' => $num ,
            'numero' => $num_est,
            'numeros' => $act,
            'actual' => $actual,
            'totalEstimacionAnt' => $totalEstimacionAnt,
            'anticipos' => $anticipos,
            'fondos' => $fondos,
            'anticipo' => $anticipo,
            'total_anticipo' => $total_anticipo,
            'conceptosExtra' => $conceptosExtra,
            'importesExtra' => $importesExtra,
            'observaciones' => $observaciones,
            'fecha_pago' => $fecha_pago
        ];
    }

    //Funcion que retorna los encabezados e historial de estimaciones para departamentos
    public function getEstimacionesDep(Request $request){
        $observaciones = $this->getObs($request->clave);
        //Variable para almacenar la fecha de pago de la estimación.
        $fecha_pago = '';

        $acumAntTotal = [];
        //Query para obtener las partidas de las estimaciones
        $estimaciones = $this->getPartidasDep($request);
        //Query que obtiene del historial de estimaciones el numero de estimaciones que se han creado.
        $act = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                        ->select('num_estimacion')
                        ->where('estimaciones.aviso_id','=',$request->clave)
                        ->orderBy('num_estimacion','desc')->distinct()->get();
        //Query que obtiene todo el historial de estimaciones del aviso de obra
        $est = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                                ->select('num_estimacion','total_estimacion')
                                ->where('estimaciones.aviso_id','=',$request->clave);
        //Filtro para numero de estimacion a buscar
        if($request->numero != '')
            $est = $est->where('num_estimacion','<=',$request->numero);

        $est = $est->orderBy('num_estimacion','desc')->distinct()->get();
        

        if(sizeof($est) == 0){
            $total_estimacion = 0;
            $num_est = 0;
        }
        else{
            //Se almacena el numero de estimación a mostrar y el monto total
            $num_est = $est[0]->num_estimacion;
            $total_estimacion = $est[0]->total_estimacion;
        }

        //Se obtienen los montos total de las estimaciones anteriores.
        $acumAntTotal = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
            ->select('total_estimacion')
            ->where('estimaciones.aviso_id','=',$request->clave)
            ->where('num_estimacion','<',$num_est)
            ->distinct('total_estimacion')->get();

        $totalEstimacionAnt = 0;

        if(sizeof($acumAntTotal)){
            //Se recorren los resultados obtenidos y se calcula el total
            foreach($acumAntTotal as $index => $acum){
                $totalEstimacionAnt += $acum->total_estimacion;
            }
        }
            
        $num = $num_est + 1;

        (sizeof($act) == 0) ? $actual = 0 : $actual = $act[0]->num_estimacion;

        //Se recorren las partidas de las estimaciones
        foreach($estimaciones as $index => $estimacion){
            $estimacion->num_estimacion = 0;
            $estimacion->costo = 0;
            $estimacion->vol = 0;
            $estimacion->acumVol = 0;
            $estimacion->acumCosto = 0;
            $estimacion->porEstimarCosto = 0;
            $estimacion->porEstimarVol = 0;
            $estimacion->costoA = 0;
            $estimacion->num_casas = 100;

            // Si hay una estimacion a mostrar
            if($num_est != 0){
                //Se obtienen el total de volumen y costo acumulados
                $acum = Hist_estimacion::select(
                        DB::raw("SUM(vol) as volumen"),
                        DB::raw("SUM(costo) as totalCosto")
                    )
                    ->where('estimacion_id','=',$estimacion->id)
                    ->where('num_estimacion','<=',$num_est)
                    ->get();
                
                //Se asignan los totales a la partida
                if( $acum[0]->volumen > 0 ){
                    $estimacion->acumVol = $acum[0]->volumen;
                    $estimacion->acumCosto = $acum[0]->totalCosto;
                }
                //Se obtiene el vol, costo y el numero actual
                $historial = Hist_estimacion::select(
                    'vol', 'costo', 'num_estimacion', 'fecha_pago'
                )
                ->where('estimacion_id','=',$estimacion->id)
                ->where('num_estimacion','=',$num_est)
                ->get();
                //Se asigna el valor actual en la estimación
                if( sizeOf($historial) > 0 ){
                    $estimacion->vol = $historial[0]->vol;
                    $estimacion->costoA = $historial[0]->costo;
                    $fecha_pago = $historial[0]->fecha_pago;
                }
            }
        }
        //Se obtienen monto total del anticipo capturado
        $anticipoT = Anticipo_estimacion::select(DB::raw("SUM(monto_anticipo) as total"))
            ->where('aviso_id','=',$request->clave)->first();
            $total_anticipo = 0;
            if($anticipoT->total != null)
                $total_anticipo = $anticipoT->total;

            $total_anticipo = $total_anticipo;
            //Se calcula el % de anticipo.
            $anticipo = round(($total_anticipo/$request->total_importe)*100,3);

        return [
            'estimaciones' => $estimaciones, 
            'total_estimacion' => $total_estimacion,
            'num_est' => $num ,
            'numero' => $num_est,
            'numeros' => $act,
            'actual' => $actual,
            'totalEstimacionAnt' => $totalEstimacionAnt,
            'observaciones' => $observaciones,
            'fecha_pago' => $fecha_pago
        ];
    }

    public function updatePartidasDep(Request $request){
        $partidas = $request->datos;

        foreach($partidas as $p){
            $partida = Estimacion::findOrFail($p['id']);
            $partida->comun = $p['comun'];
            if($partida->comun == 0)
                $partida->nivel = $p['nivel'];
            else
                $partida->nivel = NULL;
            $partida->porc = round($p['porc'],2);
            $partida->save();
        }
    }

    //Funcion que retorna las partidas de las estimaciones para el aviso de obra en excel
    public function excelEstimaciones(Request $request){
        //Llamada a la función privada que retorna los anticipos
        $anticipos = $this->getAnticipos($request->clave);
        //Llamada a la función privada que retorna los fondos de garantia
        $fondos = $this->getFG($request->clave);
        $clave = $request->clave;
        $num_casas = $request->num_casas;
        $acumAntTotal = [];
        //Llamada a la función privada que retorna los importes extra
        $importesExtra = $this->getImporteExtra($request->clave);
        //Llamada a la función privada que retorna los conceptos extra
        $conceptosExtra = $this->getConceptosExtra($request->clave);
        //Llamada a la función privada que retorna las observaciones
        $observaciones = $this->getObs($request->clave);
        //Query para obtener las partidas de las estimaciones
        $estimaciones = Estimacion::select('id', 'partida','pu_prorrateado','cant_tope')
                        ->where('aviso_id','=',$request->clave)
                        ->orderBy('id','asc')->get();
        $aviso = Ini_obra::select('num_casas')->where('id','=',$request->clave)->first();
        //Query que obtiene del historial de estimaciones el numero de estimaciones que se han creado.
        $act = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                        ->select('num_estimacion')
                        ->where('estimaciones.aviso_id','=',$request->clave)
                        ->orderBy('num_estimacion','desc')->distinct()->get();
        //Query que obtiene todo el historial de estimaciones del aviso de obra
        $est = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
                                ->select('num_estimacion', 'total_estimacion')
                                ->where('estimaciones.aviso_id','=',$request->clave);
        //Query para obtener los datos del contrato de obra
        $contrato = Ini_obra::join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                        ->select('ini_obras.emp_constructora','fraccionamientos.nombre','ini_obras.clave',
                                'total_importe2 as total_importe', 'ini_obras.total_anticipo', 'garantia_ret', 'porc_garantia_ret', 'ini_obras.anticipo',
                                'contratistas.nombre as contratista'
                        )->where('ini_obras.id','=',$request->clave)->get();
        //Se obtienen monto total del anticipo capturado
        $anticipoT = Anticipo_estimacion::select(DB::raw("SUM(monto_anticipo) as total"))
            ->where('aviso_id','=',$request->clave)->first();
        $contrato[0]->total_anticipo = 0;
        if($anticipoT->total != null)
            $contrato[0]->total_anticipo = $anticipoT->total;
            //$obra->anticipo = round(($obra->total_anticipo/$obra->total_importe)*100,3);
        $contrato[0]->anticipo = round(($contrato[0]->total_anticipo/$contrato[0]->total_importe)*100,3);
        
        //Filtro para numero de estimacion a buscar
        if($request->numero != ''){
            $est = $est->where('num_estimacion','<=',$request->numero);
        }

        $est = $est->orderBy('num_estimacion','desc')->distinct()->get();

        if(sizeof($est) == 0){
            $total_estimacion = 0;
            $num_est = 0;
        }
        else{
            //Se almacena el numero de estimación a mostrar y el monto total
            $num_est = $est[0]->num_estimacion;
            $total_estimacion = $est[0]->total_estimacion;
        }
        

        if(sizeof($est) == 0)
            $num_est = 0;
        else
            $num_est = $est[0]->num_estimacion;

        $num = $num_est + 1;

        if(sizeof($act) == 0)
            $actual = 0;
        else
            $actual = $act[0]->num_estimacion;

        //Se obtienen los montos total de las estimaciones anteriores.
        $acumAntTotal = Hist_estimacion::join('estimaciones','hist_estimaciones.estimacion_id','=','estimaciones.id')
        ->select(
            'total_estimacion'
        )
        ->where('estimaciones.aviso_id','=',$request->clave)
        ->where('num_estimacion','<',$num_est)
        ->distinct('total_estimacion')
        ->get();

        $totalEstimacionAnt = 0;

        if(sizeof($acumAntTotal)){
            //Se recorren los resultados obtenidos y se calcula el total
            foreach($acumAntTotal as $index => $acum){
                $totalEstimacionAnt += $acum->total_estimacion;
            }
        }
        
        //Se recorren las partidas de las estimaciones
        foreach($estimaciones as $index => $estimacion){
            $estimacion->num_estimacion = 0;
            $estimacion->costo = 0;
            $estimacion->vol = 0;
            $estimacion->acumVol = 0;
            $estimacion->acumCosto = 0;
            $estimacion->porEstimarCosto = 0;
            $estimacion->porEstimarVol = 0;
            $estimacion->costoA = 0;
            $estimacion->ini = '';
            $estimacion->fin = '';
            $estimacion->num_casas = $num_casas;
            if($estimacion->cant_tope != 0)
                $estimacion->num_casas = $estimacion->cant_tope;
            // Si hay una estimacion a mostrar
            if($num_est != 0){
                //Se obtienen el total de volumen y costo acumulados
                $acum = Hist_estimacion::select(
                        DB::raw("SUM(vol) as volumen"),
                        DB::raw("SUM(costo) as totalCosto")
                    )
                    ->where('estimacion_id','=',$estimacion->id)
                    ->where('num_estimacion','<=',$num_est)
                    ->get();
                //Se asignan los totales a la partida
                if( $acum[0]->volumen > 0 ){
                    $estimacion->acumVol = $acum[0]->volumen;
                    $estimacion->acumCosto = $acum[0]->totalCosto;
                }
                //Se obtiene el vol, costo y el numero actual
                $historial = Hist_estimacion::select(
                    'vol', 'costo', 'num_estimacion','ini','fin'
                )
                ->where('estimacion_id','=',$estimacion->id)
                ->where('num_estimacion','=',$num_est)
                ->get();
                //Se asigna el valor actual en la estimación
                if( sizeOf($historial) > 0 ){
                    $estimacion->vol = $historial[0]->vol;
                    $estimacion->ini = $historial[0]->ini;
                    $estimacion->fin = $historial[0]->fin;
                    $estimacion->costoA = $historial[0]->costo;
                    //$estimacion->costo = $historial[0]->costo;
                    //$estimacion->num_estimacion = $historial[0]->vol;
                }
            }
        }

        //return $contrato;
        //Creación y retorno de Excel
        return Excel::create('Estimaciones' , function($excel) use ($clave, $estimaciones, 
                $num_est, $contrato, $num_casas , $totalEstimacionAnt , $observaciones,
                $total_estimacion, $anticipos, $fondos, $importesExtra, $conceptosExtra){
            $excel->sheet($contrato[0]->clave, function($sheet) use ($clave, $estimaciones, $num_est, 
                    $contrato, $num_casas , $totalEstimacionAnt, $observaciones,
                    $total_estimacion, $anticipos, $fondos, $importesExtra, $conceptosExtra){
                
                $sheet->mergeCells('A1:L1');
                $sheet->mergeCells('A3:L3');
                $sheet->mergeCells('A4:L4');
                $sheet->mergeCells('A5:L5');
                
                $sheet->mergeCells('E7:F7');
                $sheet->mergeCells('G7:H7');
                $sheet->mergeCells('I7:J7');
                $sheet->mergeCells('K7:L7');
                $sheet->setSize('A1', 40, 60);
                $sheet->setSize('B1', 50, 20);
                $sheet->setSize('C1', 30, 20);
                $sheet->setSize('H1', 30, 20);
                $sheet->setSize('J1', 30, 20);
                $sheet->setSize('K1', 30, 20);
                $sheet->setSize('L1', 30, 20);
                $sheet->setSize('E7', 20, 20);
                $sheet->setSize('F7', 20, 20);
                $sheet->setSize('G7', 20, 20);
    
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                if($contrato[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $objDrawing->setPath(public_path('img/contratos/CONTRATOS_html_7790d2bb.png')); //your image path
                if($contrato[0]->emp_constructora == 'CONCRETANIA');
                    $objDrawing->setPath(public_path('img/contratos/logoConcretaniaObra.png')); //your image path
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);

                if($contrato[0]->emp_constructora == 'Grupo Constructor Cumbres')
                    $sheet->cell('A1', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'GRUPO CONSTRUCTOR CUMBRES, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(32);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                if($contrato[0]->emp_constructora == 'CONCRETANIA');
                    $sheet->cell('A1', function($cell) {

                        // manipulate the cell
                        $cell->setValue(  'CONCRETANIA, SA DE CV');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(20);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
                    
                $sheet->row(3, [
                    'Control de estimaciones '.$contrato[0]->nombre.' - '.$contrato[0]->clave 
                ]);
                $sheet->row(4, [
                    'Contratista '.$contrato[0]->contratista 
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

                
                $sheet->cell('A5', function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                    setlocale(LC_TIME, 'es_MX.utf8');
                    $fecha1 = new Carbon($estimaciones[0]->ini);
                    $estimaciones[0]->ini = $fecha1->formatLocalized('%d de %B de %Y');

                    $fecha2 = new Carbon($estimaciones[0]->fin);
                    $estimaciones[0]->fin = $fecha2->formatLocalized('%d de %B de %Y');

                
                $sheet->row(6, [
                    'Periodo: ', $estimaciones[0]->ini. ' al '.$estimaciones[0]->fin
                ]);
        
                $sheet->row(7, [
                    'No.', 'Paquete', 'P.U. Prorrateado', 'No. de Viviendas', 'Estimación No.'.$num_est,'', 
                    'Cantidad Tope','','Acumulado','',
                    'Por Estimar',''
                ]);


                $sheet->cells('A7:L7', function ($cells) {
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
                    
                $cont=8;

                $sheet->setColumnFormat(array(
                    'C' => '$#,##0.00',
                    'F' => '$#,##0.00',
                    'H' => '$#,##0.00',
                    'J' => '$#,##0.00',
                    'L' => '$#,##0.00'
                ));

                $suma0 = $suma1 = $suma2 = $suma3 = $suma4 = $suma5 = 0;
                
                foreach($estimaciones as $index => $detalle) {
                    $cont++;       

                    $montoTope = $volAcum = $montoAcum = $volPorEstimar = $montoPorEstimar = 0;
                    

                    $montoTope = $detalle->pu_prorrateado * $detalle->num_casas;
                    $volAcum = $detalle->acumVol + $detalle->num_estimacion;
                    $montoAcum = $detalle->acumCosto + $detalle->costo;
                    $volPorEstimar = $detalle->num_casas - $detalle->num_estimacion - $detalle->acumVol;
                    $montoPorEstimar = ($detalle->pu_prorrateado * $detalle->num_casas) - ($detalle->acumCosto + $detalle->costo);

                    $suma0+=$detalle->pu_prorrateado;
                    $suma1+=$montoTope;
                    $suma3+=$montoAcum;
                    $suma5+=$montoPorEstimar;

                    $sheet->row($cont, [
                        $index+1, 
                        $detalle->partida, 
                        $detalle->pu_prorrateado, 
                        $detalle->num_casas, 
                        $detalle->vol,
                        $detalle->costoA,
                        $detalle->num_casas,
                        $montoTope,
                        $volAcum,
                        $montoAcum,
                        $volPorEstimar,
                        $montoPorEstimar,
                    ]);	  
                    
                }
                $cont++;
                $sheet->row($cont, [
                    '', 
                    'Gran Total:', 
                    $suma0, 
                    '', 
                    '',
                    '',
                    $num_casas,
                    $suma1,
                    '',
                    $suma3,
                    '',
                    $suma5,
                ]);	 
                $num='A7:L' . $cont;
                $sheet->setBorder($num, 'thin'); 

                $total_acum_actual = $totalEstimacionAnt + $total_estimacion;
                $total_por_estimar = $contrato[0]->total_importe - $total_acum_actual;
                $porcAnticipo = $contrato[0]->anticipo/ 100;

                //'AMOR. ANTICIPO'
                $amor_total_acum_ant = $totalEstimacionAnt * ($porcAnticipo);
                $amor_total_estimacion = $total_estimacion * ($porcAnticipo);
                $amor_total_acum_actual = $amor_total_acum_ant + $amor_total_estimacion;
                $amor_total_por_estimar = $contrato[0]->total_anticipo - $amor_total_acum_actual;

                // 'F. G.'
                $fg_total_acum_ant = $totalEstimacionAnt * ($contrato[0]->porc_garantia_ret / 100);
                $fg_total_estimacion = $total_estimacion * ($contrato[0]->porc_garantia_ret / 100);
                $fg_total_acum_actual = $fg_total_acum_ant + $fg_total_estimacion;
                $fg_total_por_estimar = $contrato[0]->garantia_ret - $fg_total_acum_actual;

                // 'PAGADO'
                $pagado_total_acum_ant = $totalEstimacionAnt - ( $fg_total_acum_ant + $amor_total_acum_ant);
                $pagado_total_estimacion = $total_estimacion - ( $fg_total_estimacion + $amor_total_estimacion );
                $pagado_total_acum_actual = $total_acum_actual - ( $fg_total_acum_actual + $amor_total_acum_actual);
                $pagado_total_por_estimar = $total_por_estimar - ( $fg_total_por_estimar + $amor_total_por_estimar);

                $cont += 2;

                $sheet->row($cont, ['', '', '', 'Acum Ant', 'Esta estimación', 'Acum Actual', 'Por Estimar']);
                $sheet->row($cont+1, ['', '', 'Estimado', $totalEstimacionAnt, $total_estimacion, $total_acum_actual, $total_por_estimar]);
                $sheet->row($cont+2, ['', '', 'Amor. Anticipo', $amor_total_acum_ant, $amor_total_estimacion, $amor_total_acum_actual, $amor_total_por_estimar]);
                $sheet->row($cont+3, ['', '', 'F. G.', $fg_total_acum_ant, $fg_total_estimacion, $fg_total_acum_actual, $fg_total_por_estimar]);
                $sheet->row($cont+4, ['', '', 'Pagado', $pagado_total_acum_ant, $pagado_total_estimacion, $pagado_total_acum_actual, $pagado_total_por_estimar]);

                $cont2 = $cont+4;
                $num='C'.$cont.':G' . $cont2;
                $sheet->setBorder($num, 'thin'); 

                $sheet->setColumnFormat(array(
                    'A'.$cont.':G'.$cont2 => '$#,##0.00'
                ));

                $sheet->row($cont+6, ['', '', '', '','Esta estimacion']);
                $sheet->row($cont+7, ['', '', '', '',$pagado_total_estimacion]);
                $cont2 = $cont+7;
                $sheet->cell('E'.$cont2, function($cell) {

                    // manipulate the cell
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                
                });
                $sheet->setColumnFormat(array(
                    'A'.$cont2.':G'.$cont2 => '$#,##0.00'
                ));

                ///////////// ANTICIPOS

                $sheet->row($cont2+1, ['','Anticipo', $contrato[0]->total_anticipo]);
            
                $cont2+=1;
                $cont3=$cont2;
                $totalAnticipo = 0;
                foreach($anticipos as $index => $anticipo) {
                    $cont2++;
                    $sheet->row($cont2, 
                        ['','Pago de Anticipo de Vivienda ('.$anticipo->fecha_anticipo.')', $anticipo->monto_anticipo]
                    );
                    $totalAnticipo += $anticipo->monto_anticipo;
                }
                $cont2+=1;
                $sheet->row($cont2, 
                        ['','', $totalAnticipo]
                    );
                
                $num='B'.$cont3.':C' . $cont2;
                $sheet->setBorder($num, 'thin'); 
                $sheet->setColumnFormat(array(
                    'C'.$cont3.':C'.$cont2 => '$#,##0.00'
                ));
            
            ///////////// FONDOS G.
                $sheet->row($cont2+2, ['','Fondo de Garantia']);

                $cont2+=2;
                $cont3=$cont2;
                $totalGarantia = 0;
                foreach($fondos as $index => $fondo) {
                    $cont2++;
                    $sheet->row($cont2, 
                        ['','Pago de '.$fondo->cantidad.' FG ('.$fondo->fecha_fg.')',$fondo->cantidad,$fondo->monto_fg]
                    );
                    $totalGarantia += $fondo->monto_fg;
                }
                $cont2+=1;
                $sheet->row($cont2, 
                        ['','','', $totalGarantia]
                    );
                
                $num='B'.$cont3.':D' . $cont2;
                $sheet->setBorder($num, 'thin'); 
                $sheet->setColumnFormat(array(
                    'D'.$cont3.':D'.$cont2 => '$#,##0.00',
                    'C'.$cont3.':C'.$cont2 => '#,##0.00'
                ));


            ///////////// OBRAS EXTRA
                $cont2+=2;
                
                $cont3=$cont2;
                if(sizeof($importesExtra)){
                    $saldoExtra = $importesExtra[0]->impExtra;
                    $sheet->row($cont2, 
                        ['','Obra extra:',$importesExtra[0]->impExtra]
                    );
                }
                else{
                    $saldoExtra = 0;
                    $sheet->row($cont2, 
                        ['','Obra extra:',0]
                    );
                }
                
                $cont2++;
                $sheet->row($cont2, 
                        ['','Concepto','Importe','Fecha']
                    );
                foreach($conceptosExtra as $index => $concepto) {
                    $cont2++;
                    
                    $sheet->row($cont2, 
                        ['',$concepto->concepto,$concepto->importe,$concepto->fecha]
                    );
                    $saldoExtra -= $concepto->importe;
                }
                $cont2+=2;
                $sheet->row($cont2, 
                        ['','Saldo',$saldoExtra]
                    );
                
                $num='B'.$cont3.':D' . $cont2;
                $sheet->setBorder($num, 'thin'); 
            
            });
        })->download('xls');
    }

    //Función para registrar una nueva estimacion en el historial
    public function storeEstimacion(Request $request){
        $estimacion = new Hist_estimacion();
        $estimacion->estimacion_id = $request->estimacion_id;
        $estimacion->num_estimacion = $request->num_estimacion;
        $estimacion->vol = $request->vol;
        $estimacion->costo = $request->costo;
        $estimacion->total_estimacion = $request->total_estimacion;
        $estimacion->total_pagado = $request->total_pagado;
        $estimacion->ini = $request->periodo1;
        $estimacion->fin = $request->periodo2;
        $estimacion->fecha_pago = $request->fecha_pago;
        $estimacion->save();

        if($request->tipo_proyecto == 2)
            $this->updateAvace($request);
    }

    public function updateAvace(Request $request){
        $topeNivel = $this->totalPorcentajeNivel($request);
        return $topeComun = $this->totalPorcentajeComun($request);

        $actualNivel = $this->totalActualNivel($request);
        $actualComun = $this->totalActualComun($request);

        $porcNivel = ($actualNivel * 65)/$topeNivel;
        $porcComun = ($actualNivel * 35)/$topeComun;

        $avanceReal = round( $porcNivel + $porcComun, 2 );

        $departamentos = $this->getDepManzana($request);

        foreach($departamentos as $dep){
            $lote = Licencia::findOrFail($dep['id']);
            $lote->avance = $avanceReal;
            $lote->save();
        }

    }

    private function getDepManzana(Request $request){
        return Ini_obra_lote::select('lote_id as id')
                ->where('manzana','=',$request->nivel)
                ->where('ini_obra_id','=',$request->clave)
                ->get();
    }

    private function totalPorcentajeNivel(Request $request){
        $estimaciones = Estimacion::select(DB::raw("SUM(estimaciones.pu_prorrateado) as total"))
                    ->where('estimaciones.aviso_id','=',$request->clave)
                    ->where('nivel','=',$request->nivel)
                    ->where('comun','=',0)
                    ->first();
        
        return round($estimaciones->total,2);
    }

    private function totalActualNivel(Request $request){
        $estimaciones = Estimacion::select('id')
                    ->where('estimaciones.aviso_id','=',$request->clave)
                    ->where('nivel','=',$request->nivel)
                    ->where('comun','=',0)
                    ->get();

        if(sizeof($estimaciones)){
            $actual = Hist_estimacion::select(DB::raw("SUM(hist_estimaciones.costo) as total"))
                ->whereIn('estimacion_id',$estimaciones)
                ->first();

            return round($actual->total,2);

        }
            
        return 0;

        
    }

    private function totalActualComun(Request $request){
        $estimaciones = Estimacion::select('id')
                    ->where('estimaciones.aviso_id','=',$request->clave)
                    ->where('comun','=',1)
                    ->get();

        if(sizeof($estimaciones))
            $actual = Hist_estimacion::select(DB::raw("SUM(hist_estimaciones.costo) as total"))
                ->whereIn('estimacion_id',$estimaciones)
                ->first();

        return round($actual->total,2);
    }

    private function totalPorcentajeComun(Request $request){
        $estimaciones = Estimacion::select(DB::raw("SUM(estimaciones.pu_prorrateado) as total"))
                    ->where('estimaciones.aviso_id','=',$request->clave)
                    ->where('comun','=',1)
                    ->first();
        
        return round($estimaciones->total,2);
    }

    //Función para actualizar una nueva estimacion en el historial
    public function updateEstimacion(Request $request){
        //Se busca registro que corresponda a la partida a actualizar
        $est = Hist_estimacion::select('id','ini','fin')
                    ->where('estimacion_id','=',$request->estimacion_id)
                    ->where('num_estimacion','=',$request->num_estimacion)
                    ->get();
        //En caso de econtrar una partida
        if(sizeof($est)){
            //Se accede al registro
            $estimacion = Hist_estimacion::findOrFail($est[0]->id);
            //En caso de cambiar a 0, se elimina el registro
            if($request->vol == 0){
                $estimacion->delete();
            }
            else{
                //En caso contrario se actualiza la información
                $estimacion->vol = $request->vol;
                $estimacion->costo = $request->costo;
                $estimacion->total_estimacion = $request->total_estimacion;
                $estimacion->total_pagado = $request->total_pagado;
                $estimacion->save();
            }
        }
        //En caso de no encontrar un registro
        else{
            //Se crea un registro nuevo
            $estimacion = new Hist_estimacion();
            $estimacion->estimacion_id = $request->estimacion_id;
            $estimacion->num_estimacion = $request->num_estimacion;
            $estimacion->vol = $request->vol;
            $estimacion->costo = $request->costo;
            $estimacion->total_estimacion = $request->total_estimacion;
            $estimacion->total_pagado = $request->total_pagado;
            $estimacion->save();
        }

        
        if($request->tipo_proyecto == 2)
            $this->updateAvace($request);
    }

    //Función para registrar un anticipo
    public function storeAnticipo(Request $request){
        $anticipo = new Anticipo_estimacion();
        $anticipo->aviso_id = $request->aviso_id;
        $anticipo->fecha_anticipo = $request->fecha_anticipo;
        $anticipo->monto_anticipo = $request->monto_anticipo;
        $anticipo->save();
    }
    
    //Funciíon para registrar un Fondo de Garantia
    public function storeFG(Request $request){
        $anticipo = new Fg_estimacion();
        $anticipo->aviso_id = $request->aviso_id;
        $anticipo->cantidad = $request->cantidad;
        $anticipo->monto_fg = $request->monto_fg;
        $anticipo->fecha_fg = $request->fecha_fg;
        $anticipo->save();
    }
    //Función para obtener los anticipos de un aviso de obra.
    public function getAnticipos($aviso){
        $anticipos = Anticipo_estimacion::select('id','fecha_anticipo','monto_anticipo')
                    ->where('aviso_id','=',$aviso)->orderBy('fecha_anticipo','asc')->get();
        return $anticipos;
    }
    //Función para obtener los fondos de garantia de un aviso de obra.
    public function getFG($aviso){
        $fondos = Fg_estimacion::select('id','cantidad','monto_fg','fecha_fg')
                    ->where('aviso_id','=',$aviso)->orderBy('id','asc')->get();
        return $fondos;
    }
    //Función para exportar a excel el formato para SIROC
    public function imprimirSiroc(Request $request){
        //Query para obtener los datos del aviso de obra
        $aviso = Ini_obra::join('contratistas','ini_obras.contratista_id','=','contratistas.id')
            ->join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
            ->select('ini_obras.id','ini_obras.clave','ini_obras.f_ini','ini_obras.f_fin',
                'ini_obras.total_importe',
                'ini_obras.total_superficie',
                'ini_obras.emp_constructora', 
                'ini_obras.calle1', 
                'ini_obras.calle2',
                'contratistas.nombre as contratista',
                'fraccionamientos.nombre as proyecto',
                'fraccionamientos.calle',
                'fraccionamientos.colonia',
                'fraccionamientos.estado',
                'fraccionamientos.ciudad',
                'fraccionamientos.cp',
                'fraccionamientos.numero',
                'ini_obras.tipo'
            )
            ->where('ini_obras.id','=',$request->id)->get();
        //Creación y retorno del archivo excel.
        return Excel::create('SIROC '.$aviso[0]->clave , function($excel) use ($aviso){
            $excel->sheet($aviso[0]->clave, function($sheet) use ($aviso){
                
                /////////// MergeCells
                    $sheet->mergeCells('A1:H1'); $sheet->setBorder('A1:H1', 'thick');
                    $sheet->mergeCells('A6:H6'); $sheet->setBorder('A6:H6', 'thick');
                    $sheet->mergeCells('A14:H14'); $sheet->setBorder('A14:H14', 'thick');
                    $sheet->mergeCells('A20:H20'); $sheet->setBorder('A20:H20', 'thick');
                    $sheet->mergeCells('A38:H38'); $sheet->setBorder('A38:H38', 'thick');
                    $sheet->mergeCells('A39:H44'); $sheet->setBorder('A39:H44', 'thin');

                   

                    $sheet->mergeCells('A3:B3'); $sheet->setBorder('C3:C4', 'thin');
                    $sheet->mergeCells('A4:B4'); 

                    $sheet->mergeCells('A8:B8'); $sheet->setBorder('C8:C12', 'thin');
                    $sheet->mergeCells('A9:B9');
                    $sheet->mergeCells('A10:B10');
                    $sheet->mergeCells('A11:B11');
                    $sheet->mergeCells('A12:B12');
                    

                    $sheet->mergeCells('A18:B18');  $sheet->setBorder('C16:C18', 'thin');
                    $sheet->mergeCells('A16:B16');
                    $sheet->mergeCells('A17:B17');

                    $sheet->mergeCells('C3:H3');
                    $sheet->mergeCells('C4:H4');
                    $sheet->mergeCells('C8:H8');
                    $sheet->mergeCells('C9:H9');
                    $sheet->mergeCells('C10:H10');
                    $sheet->mergeCells('C11:H11');
                    $sheet->mergeCells('C12:H12');

                    $sheet->mergeCells('C18:H18');
                    $sheet->mergeCells('C16:H16');
                    $sheet->mergeCells('C17:H17');
                    $sheet->mergeCells('C25:H25'); $sheet->setBorder('C25', 'thin');

                    $sheet->mergeCells('E31:G31'); $sheet->setBorder('C25', 'thin');
                    $sheet->mergeCells('E34:G34'); $sheet->setBorder('E31', 'thin');
                    $sheet->mergeCells('E36:F36'); $sheet->setBorder('C34', 'thin');

                    $sheet->mergeCells('A22:B22');
                    $sheet->mergeCells('A24:B24');
                    $sheet->mergeCells('A26:B26');
                    $sheet->mergeCells('A29:B29');
                    $sheet->mergeCells('A33:B33'); $sheet->setBorder('C36', 'thin');
                    $sheet->mergeCells('A36:B36'); $sheet->setBorder('G36', 'thin');

                    $sheet->setBorder('D22', 'thick');
                    $sheet->setBorder('F22', 'thick');
                    $sheet->setBorder('D27', 'thick');
                    $sheet->setBorder('F27', 'thick');
                    $sheet->setBorder('H27', 'thick');
                    
                    
                    $sheet->setSize('A1', 35, 20);
                    $sheet->setSize('C1', 20, 20);
                    $sheet->setSize('D1', 4, 20);
                    $sheet->setSize('F1', 4, 20);
                    $sheet->setSize('E1', 25, 20);
                    $sheet->setSize('H1', 4, 20);
                    $sheet->setSize('G1', 25, 20);

    
                $sheet->cell('A1', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'SIROC');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(14);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('A6', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'DIRECCION DE LA OBRA');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('A38', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'OBSERVACIONES');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('A14', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'UBICACIÓN DE LA OBRA');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });

                $sheet->cell('A20', function($cell) {
                    // manipulate the cell
                    $cell->setValue(  'OBRA');
                    $cell->setFontFamily('Arial Narrow');
                    $cell->setFontSize(11);
                    $cell->setFontWeight('bold');
                    $cell->setAlignment('center');
                });
                
                $ciudad = $aviso[0]->ciudad.', '.$aviso[0]->estado;
    
                $sheet->setCellValue('A3', 'NOMBRE CONTRATISTA: ' ); 
                $sheet->setCellValue('A4', 'REFERENCIA CONTRATO: ' ); 
                $sheet->setCellValue('A8', 'CALLE:' ); 
                $sheet->setCellValue('A9', 'NUMERO INT/EXT: ' ); 
                $sheet->setCellValue('A10', 'COLONIA: ' ); 
                $sheet->setCellValue('A11', 'CIUDAD/EDO: ' ); 
                $sheet->setCellValue('A12', 'CODIGO POSTAL: ' ); 
                $sheet->setCellValue('A16', 'ENTRE CALLE: ' ); 
                $sheet->setCellValue('A17', 'ENTRE CALLE: ' ); 
                $sheet->setCellValue('A18', 'ENTRE CALLE: ' ); 

                $sheet->setCellValue('A22', 'CLASE DE OBRA: ' ); 
                $sheet->setCellValue('A24', 'TIPO DE OBRA: ' ); 
                $sheet->setCellValue('A26', 'TIPO DE PATRON: ' ); 
                $sheet->setCellValue('A29', 'MONTO DE LA OBRA: ' ); 
                $sheet->setCellValue('A33', 'SUPERFICIE DE CONSTRUCCIÓN: ' ); 
                $sheet->setCellValue('A36', 'FECHA DE INICIO: ' );  

                $sheet->setCellValue('C3', strtoupper($aviso[0]->contratista) ); 
                $sheet->setCellValue('C4', strtoupper($aviso[0]->clave) ); 
                $sheet->setCellValue('C8', strtoupper($aviso[0]->calle) ); 
                $sheet->setCellValue('C9', 'NO. '.$aviso[0]->numero); 
                $sheet->setCellValue('C10', strtoupper($aviso[0]->colonia) ); 
                $sheet->setCellValue('C11', strtoupper($ciudad) ); 
                $sheet->setCellValue('C12', strtoupper($aviso[0]->cp) ); 

                if($aviso[0]->calle1 != null && $aviso[0]->calle2 != null){
                    $sheet->setCellValue('C16', strtoupper($aviso[0]->calle1) ); 
                    $sheet->setCellValue('C17', strtoupper($aviso[0]->calle2) ); 
                }

                $sheet->setCellValue('C22', 'PRIVADA' ); 
                $sheet->setCellValue('D22', 'X' ); 
                $sheet->setCellValue('E22', 'PUBLICA' ); 
                
                $sheet->setCellValue('C25', 'EDIFICACION DE '.strtoupper($aviso[0]->tipo) ); 

                $sheet->setCellValue('C27', 'PROPIETARIO' ); 
                $sheet->setCellValue('D27', 'X' ); 
                $sheet->setCellValue('E27', 'CONTRATISTA' ); 
                $sheet->setCellValue('G27', 'SUBCONTRATISTA' );

                $sheet->setCellValue('D31', '$' );
                $sheet->setCellValue('E31',  $aviso[0]->total_importe);

                $sheet->setCellValue('C34', $aviso[0]->total_superficie ); 
                $sheet->setCellValue('E34', 'm2' );

                $sheet->setCellValue('C36', $aviso[0]->f_ini ); 
                $sheet->setCellValue('E36', 'FECHA DE TERMINO: ' ); 
                $sheet->setCellValue('G36', $aviso[0]->f_fin ); 
                
                $sheet->setColumnFormat(array(
                    'E31' => '#,##0.00',
                ));

                $sheet->setColumnFormat(array(
                    'C34' => '#,##0.00',
                ));
    
                // $sheet->cell('G'.$contador01, function ($cell) {
                //     // Set font weight to bold
                    
                //     $cell->setAlignment('left');
                // });
                
    
                // $sheet->setBorder($num, 'thin');
            });
            }
            
            )->download('xls');
            
    }
    //Función para actualizar el importe total del aviso de obra
    public function updateImporTotal(Request $request){
        $iniObra = Ini_obra::findOrFail($request->id);
        $iniObra->total_importe2 = $request->total_importe;
        $iniObra->garantia_ret = $request->importe_garantia;
        $iniObra->save();
    }
    //Función para registar un nuevo importe extra
    public function storeImporteExtra(Request $request){
        $importe = new Importe_extra();
        $importe->impExtra = $request->impExtra;
        $importe->fechaExtra = $request->fechaExtra;
        $importe->aviso_id = $request->clave;
        $importe->save();
    }
    //Función para registar un nuevo concepto extra
    public function storeConceptoExtra(Request $request){
        $concepto = new Concepto_extra();
        $concepto->fecha = $request->fecha;
        $concepto->concepto = $request->concepto;
        $concepto->importe = $request->importe;
        $concepto->aviso_id = $request->clave;
        $concepto->save();
    }

    //Función que retorna los datos necesarios para el estado de cuenta de un contrato de obra.
    public function getEdoCuenta($clave){
        //Se inicializan variables para los totales
        $totalAnt = $totalFG = $totalEst = $totalExtra = 0;
        //Query que obtiene los datos generales del contrato
        $contrato = Ini_obra::join('fraccionamientos','ini_obras.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('contratistas','ini_obras.contratista_id','=','contratistas.id')
                        ->select('ini_obras.emp_constructora','fraccionamientos.nombre','ini_obras.clave',
                                'total_importe2 as total_importe','contratistas.nombre as contratista'
                        )->where('ini_obras.id','=',$clave)->get();
        //Llamada a la función privada que retorna los anticipos del contrato
        $anticipos = $this->getAnticipos($clave);
        //Llamada a la función privada que retorna los fondos de garantia del contrato
        $fg = $this->getFG($clave);
        //Llamada a la función privada que retorna los conceptos del contrato.
        $conceptosExtra = $this->getConceptosExtra($clave);

        if(sizeof($anticipos))
        //Se recorren los anticipos para calcular el monto total
            foreach($anticipos as $index => $ant)
                $totalAnt += $ant->monto_anticipo;

        if(sizeof($fg))
        //Se recorren los fondos de garantia para calcular el monto total
            foreach($fg as $index => $f)
                $totalFG += $f->monto_fg;

        if(sizeof($conceptosExtra))
        //Se recorren los conceptos extras para calcular el monto total
            foreach($conceptosExtra as $index => $c)
                $totalExtra += $c->importe;
        //Query que obtiene las partidas para las estimaciones del contrato
        $estimaciones = Estimacion::select('id')->where('aviso_id','=',$clave)->get();
        $arrayEst = [];//Arreglo para almacenar los id de las partidas
        //Se recorren las partidas y se almacenan en el arreglo
        foreach($estimaciones as $index => $est){
            array_push($arrayEst,$est->id);
        }
        //Query para obtener el numero de estimaciones que se han generado
        $number_est = Hist_estimacion::select('num_estimacion','ini','fin','fecha_pago','total_estimacion','total_pagado')
                    ->whereIn('estimacion_id',$arrayEst)->where('ini','!=',NULL)->orderBy('num_estimacion','asc')->distinct()->get();

        if(sizeof($number_est))
            foreach($number_est as $index => $n)
                //Se calcula el total estimado
                $totalEst += $n->total_pagado;
        //Regreso de la información
        return [
            'contrato' => $contrato,
            'anticipos' => $anticipos,
            'fg' => $fg,
            'estimaciones' => $arrayEst,
            'num_est' => $number_est,
            'totalAnt' => $totalAnt,
            'totalFG' => $totalFG,
            'totalEst' => $totalEst,
            'totalExtra' => $totalExtra,
            'conceptosExtra' => $conceptosExtra,
        ];

    }

    //Funcion para indicar el numero topo de partida
    public function editCantTope(Request $request){
        $estimacion = Estimacion::findOrFail($request->id);
        $estimacion->cant_tope = $request->cant_tope;
        $estimacion->save();
    }

    //Función para retornar el estado de cuenta del aviso de obra.
    public function excelEdoCuenta(Request $request){
        $clave = $request->clave;
        //Llamada a la función privada que retorna los datos necesarios para el estado de cuenta
        $respuesta = $this->getEdoCuenta($clave);

        $contrato = $respuesta['contrato'];
        $anticipos = $respuesta['anticipos'];
        $fg = $respuesta['fg'];
        $arrayEst = $respuesta['estimaciones'];
        $number_est = $respuesta['num_est'];
        $totalAnt = $respuesta['totalAnt'];
        $totalFG = $respuesta['totalFG'];
        $totalEst = $respuesta['totalEst'];
        $totalExtra = $respuesta['totalExtra'];
        $conceptosExtra = $respuesta['conceptosExtra'];
        //Creación y retorno del estado de cuenta.
        return Excel::create('Resumen Estimaciones' , function($excel) use (
            $contrato, $number_est, $anticipos, $fg, $totalAnt , $totalFG , $totalEst, $conceptosExtra, $totalExtra
            ){
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
                        $fecha = $est->fin;
                        if($est->fecha_pago != NULL)
                            $fecha = $est->fecha_pago;
    
                        $sheet->row($renglon, [
                            $est->num_estimacion,
                            $fecha,
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
        })->download('xls');
    }

}