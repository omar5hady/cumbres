<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ini_obra;
use App\Ini_obra_lote;
use App\Lote;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use NumerosEnLetras;

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
            'ini_obras.contratista_id','ini_obras.descripcion_corta','ini_obras.descripcion_larga','ini_obras.iva','ini_obras.tipo')
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
        'ini_obra_lotes.descripcion','ini_obra_lotes.id','ini_obra_lotes.ini_obra_id','ini_obra_lotes.lote_id')
        ->where('ini_obra_lotes.ini_obra_id','=',$id)
        ->orderBy('ini_obra_lotes.id', 'desc')->get();
         
        return ['detalles' => $detalles];
    }
 
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        try{
            DB::beginTransaction(); 
            $ini_obra = new Ini_obra();
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $request->f_ini;
            $ini_obra->f_fin = $request->f_fin;
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
                $lotes->save();

                if($det['lote_id']>0){
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
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
        $buscar = $request->buscar;
        $lotesManzana = Lote::select('manzana')
                        ->where('fraccionamiento_id','=',$buscar)
                        ->where('ini_obra', '=', '1')
                        ->where('aviso','=','0')->distinct()
                        ->get();

                        return ['lotesManzana' => $lotesManzana];
    }

    public function select_lotes (Request $request)
    {
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $lotes = Lote::select('num_lote','id')
                        ->where('fraccionamiento_id','=',$buscar2)
                        ->where('manzana','=',$buscar)
                        ->where('ini_obra', '=', '1')
                        ->where('aviso','=','0')
                        ->get();

                        return ['lotes' => $lotes];
    }

    public function select_datos_lotes (Request $request)
    {
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
 
        try{
            DB::beginTransaction(); 
            $ini_obra = Ini_obra::findOrFail($request->id);
            $ini_obra->fraccionamiento_id = $request->fraccionamiento_id;
            $ini_obra->contratista_id = $request->contratista_id;
            $ini_obra->clave = $request->clave;
            $ini_obra->f_ini = $request->f_ini;
            $ini_obra->f_fin = $request->f_fin;
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
                $lotes->save();

                if($det['lote_id']>0){
                    $lote = Lote::findOrFail($det['lote_id']);
                    $lote->aviso=$ini_obra->clave;
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
        $lote = Lote::findOrFail($loteIni->lote_id);
                $lote->aviso = '0';
                $lote->save();
        $loteIni->delete();

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

   public function contratoObraPDF(Request $request  ){
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
    /*->where('ini_obras.id','=',$id)*/
    ->orderBy('ini_obras.id', 'desc')->take(1)->get();

    setlocale(LC_TIME, 'es');
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

}
