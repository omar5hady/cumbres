<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ruv;
use App\Lote;
use Auth;
use App\Obs_ruv;
use DB;
use Excel;
use Carbon\Carbon;

class RuvController extends Controller
{
    public function indexLotes (Request $request){

        $proyecto = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->select('lotes.id','etapas.num_etapa','fraccionamientos.nombre as proyecto','lotes.num_lote',
                            'modelos.nombre as modelo', 'lotes.manzana','lotes.calle','lotes.numero','lotes.paq_ruv',
                            'lotes.terreno','lotes.construccion','lotes.paq_ruv');

        if($proyecto == ''){
            $lotes = $query->where('lotes.paq_ruv','=',NULL);
        }
        else{
            if($etapa == '' && $manzana == ''){
                $lotes = $query->where('lotes.paq_ruv','=',NULL)
                    ->where('lotes.fraccionamiento_id','=',$proyecto);
            }
            elseif($etapa != '' && $manzana == ''){
                $lotes = $query->where('lotes.paq_ruv','=',NULL)
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('lotes.etapa_id','=',$etapa);
            }
            elseif($etapa == '' && $manzana != ''){
                $lotes = $query->where('lotes.paq_ruv','=',NULL)
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('lotes.manzana','like','%'.$manzana.'%');
            }
            elseif($etapa != '' && $manzana != ''){
                $lotes = $query->where('lotes.paq_ruv','=',NULL)
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','like','%'.$manzana.'%');
            }
        }

        if($request->empresa != ''){
            $lotes = $lotes->where('lotes.emp_constructora','=',$request->empresa);
        }

        $lotes = $lotes->orderBy('proyecto','asc')->orderBy('etapas.num_etapa','asc')
            ->orderBy('lotes.num_lote','asc')->paginate(10);
        

        return[
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes'=>$lotes
        ];

    }

    public function historialSolicitudes (Request $request){

        $proyecto = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;

        $fecha = $request->fecha;
        $fecha2 = $request->fecha2;

        $query = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('ruvs','lotes.id','=','ruvs.id')
                    ->select('lotes.id','etapas.num_etapa','fraccionamientos.nombre as proyecto','lotes.num_lote',
                            'modelos.nombre as modelo', 'lotes.manzana','lotes.calle','lotes.numero','lotes.paq_ruv',
                            'lotes.terreno','lotes.construccion','ruvs.fecha_siembra');

        if($fecha == ''){
            if($proyecto == ''){
                $lotes = $query->where('lotes.paq_ruv','!=',NULL);
            }
            else{
                if($etapa == '' && $manzana == ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto);
                }
                elseif($etapa != '' && $manzana == ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa);
                }
                elseif($etapa == '' && $manzana != ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.manzana','like','%'.$manzana.'%');
                }
                elseif($etapa != '' && $manzana != ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('lotes.manzana','like','%'.$manzana.'%');
                }
    
            }
        }
        else{
            if($proyecto == ''){
                $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                    ->whereBetween('ruvs.fecha_siembra', [$fecha, $fecha2]);
            }
            else{
                if($etapa == '' && $manzana == ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->whereBetween('ruvs.fecha_siembra', [$fecha, $fecha2]);
                }
                elseif($etapa != '' && $manzana == ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->whereBetween('ruvs.fecha_siembra', [$fecha, $fecha2])
                        ->where('lotes.etapa_id','=',$etapa);
                }
                elseif($etapa == '' && $manzana != ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->whereBetween('ruvs.fecha_siembra', [$fecha, $fecha2])
                        ->where('lotes.manzana','like','%'.$manzana.'%');
                }
                elseif($etapa != '' && $manzana != ''){
                    $lotes = $query->where('lotes.paq_ruv','!=',NULL)
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->whereBetween('ruvs.fecha_siembra', [$fecha, $fecha2])
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('lotes.manzana','like','%'.$manzana.'%');
                }
    
            }
        }

        if($request->empresa != ''){
            $lotes = $lotes->where('lotes.emp_constructora','=',$request->empresa);
        }
        
        $lotes = $lotes->orderBy('proyecto','asc')->orderBy('etapas.num_etapa','asc')
        ->orderBy('lotes.num_lote','asc')->paginate(10);

        return[
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes'=>$lotes
        ];

    }

    public function solicitarRuv(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $id = $request->id;
       
        try {
        DB::beginTransaction();
            //FindOrFail se utiliza para buscar lo que recibe de argumento
            $lote = Lote::findOrFail($id);
            $lote->paq_ruv = $request ->paquete;
            $lote->save();
            
            //Aqui se deberia hacer toda la asignacion para la tabla ruvs
                $ruv = new Ruv();
                $ruv->id = $lote->id;
                $ruv->fecha_siembra = Carbon::today()->format('ymd');
                $ruv->user_siembra = Auth::user()->id;
                $ruv->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    public function indexRuv(Request $request){
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $paquete = $request->b_paquete;

        $query = Ruv::join('lotes','ruvs.id','=','lotes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('personal','ruvs.user_siembra','=','personal.id')
                ->leftjoin('empresas_verificadoras as emp', 'ruvs.empresa_id','=','emp.id')
                ->select('lotes.id','lotes.num_lote','lotes.paq_ruv','fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa','modelos.nombre as modelo','emp.empresa','ruvs.fecha_siembra',
                        'ruvs.fecha_carga','ruvs.num_cuv','ruvs.fecha_asignacion','fecha_revision',
                        'ruvs.fecha_dtu', 'lotes.emp_constructora',
                        'personal.nombre','personal.apellidos','lotes.manzana');

        
        if($buscar == '' && $etapa == '' && $manzana == '' && $lote == ''){
            $lotes = $query;
        }
        elseif($buscar != ''  && $lote == ''){
            $lotes = $query->where('lotes.fraccionamiento_id','=',$buscar)
                            ->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%');
        }
        elseif($buscar != '' && $lote != ''){
            $lotes = $query->where('lotes.fraccionamiento_id','=',$buscar)
                            ->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%')
                            ->where('lotes.num_lote','=',$lote);
        }
        elseif($buscar == '' && $lote != ''){
            $lotes = $query->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%')
                            ->where('lotes.num_lote','=',$lote);
        }

        if($paquete != ''){
            $lotes = $lotes->where('lotes.paq_ruv','like','%'.$paquete.'%');
        }

        if($request->empresa != ''){
            $lotes = $lotes->where('lotes.emp_constructora','=',$request->empresa);
        }
       
        

             $lotes= $lotes->orderBy('proyecto','asc')
                    ->orderBy('etapas.num_etapa','asc')
                    ->orderBy('modelo','asc')
                    ->orderBy('lotes.num_lote','asc')
                    ->orderBy('lotes.paq_ruv','asc')->paginate(10);


        return[
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes'=>$lotes
        ];
    }

    public function cargaInfo(Request $request){
        $ruv = Ruv::findOrFail($request->id);
        $ruv->fecha_carga = $request->fecha;
        $ruv->save();
    }

    public function obtenerCuv(Request $request){
        $ruv = Ruv::findOrFail($request->id);
        $ruv->num_cuv = $request->numeroCuv;
        $ruv->save();
    }

    public function asignarVerificador(Request $request){
        $ruv = Ruv::findOrFail($request->id);
        $fecha = Carbon::now();
        $ruv->empresa_id = $request->empresa;
        $ruv->fecha_asignacion = $fecha;
        $ruv->save();
    }

    public function revDocumental(Request $request){
        $ruv = Ruv::findOrFail($request->id);
        $fecha = Carbon::now();
        $ruv->fecha_revision = $fecha;
        $ruv->save();
    }

    public function dtu(Request $request){
        $ruv = Ruv::findOrFail($request->id);
        $ruv->fecha_dtu = $request->fecha;
        $ruv->save();
    }

    public function indexComentarios(Request $request){
        if(!$request->ajax())return redirect('/');
        $id = $request->id;
        $observacion = Obs_ruv::select('observacion','usuario','created_at','id')
                    ->where('ruv_id','=', $id)->orderBy('created_at','desc')->get();

        return [
            'observacion' => $observacion
        ];
    }

    public function storeComentarios(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_ruv();
        $observacion->ruv_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function excelRuv(Request $request){
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $paquete = $request->b_paquete;

        $query = Ruv::join('lotes','ruvs.id','=','lotes.id')
                ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('personal','ruvs.user_siembra','=','personal.id')
                ->leftjoin('empresas_verificadoras as emp', 'ruvs.empresa_id','=','emp.id')
                ->select('lotes.id','lotes.num_lote','lotes.paq_ruv','fraccionamientos.nombre as proyecto',
                        'etapas.num_etapa','modelos.nombre as modelo','emp.empresa','ruvs.fecha_siembra',
                        'ruvs.fecha_carga','ruvs.num_cuv','ruvs.fecha_asignacion','fecha_revision',
                        'ruvs.fecha_dtu',
                        'personal.nombre','personal.apellidos','lotes.manzana');

        
        if($buscar == '' && $etapa == '' && $manzana == '' && $lote == ''){
            $lotes = $query;
        }
        elseif($buscar != ''  && $lote == ''){
            $lotes = $query->where('lotes.fraccionamiento_id','=',$buscar)
                            ->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%');
        }
        elseif($buscar != '' && $lote != ''){
            $lotes = $query->where('lotes.fraccionamiento_id','=',$buscar)
                            ->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%')
                            ->where('lotes.num_lote','=',$lote);
        }
        elseif($buscar == '' && $lote != ''){
            $lotes = $query->where('etapas.num_etapa','like','%'.$etapa.'%')
                            ->where('lotes.manzana','like','%'.$manzana.'%')
                            ->where('lotes.num_lote','=',$lote);
        }

        if($paquete != ''){
            $lotes = $lotes->where('lotes.paq_ruv','like','%'.$paquete.'%');
        }

        if($request->empresa != ''){
            $lotes = $lotes->where('lotes.emp_constructora','=',$request->empresa);
        }
       
        

             $lotes= $lotes->orderBy('proyecto','asc')
                    ->orderBy('etapas.num_etapa','asc')
                    ->orderBy('modelo','asc')
                    ->orderBy('lotes.num_lote','asc')
                    ->orderBy('lotes.paq_ruv','asc')->get();


            return Excel::create(
                'RUV',
                function ($excel) use ($lotes) {
                    $excel->sheet('RUV', function ($sheet) use ($lotes) {
    
                        $sheet->row(1, [
                            'Proyecto', 'Etapa', 'Manzana', '# Lote', 'Modelo', 'Paquete',
                            'Fecha', 'Solicitante', 'Carga de información', 'Numero CUV', 
                            'Fecha asignacion', 'Empresa asignada', 'Rev. Documental', 'DTU'
                        ]);
    
    
                        $sheet->cells('A1:N1', function ($cells) {
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
    
                        $cont = 1;
    
                        foreach ($lotes as $index => $lote) {
                            $cont++;
                            if ($lote->fecha_asignacion) {
                                setlocale(LC_TIME, 'es_MX.utf8');
                                $tiempo = new Carbon($lote->fecha_asignacion);
                                $lote->fecha_asignacion = $tiempo->formatLocalized('%d de %B de %Y');
                            }
    
    
                            if ($lote->fecha_siembra) {
                                setlocale(LC_TIME, 'es_MX.utf8');
                                $t3 = new Carbon($lote->fecha_siembra);
                                $lote->fecha_siembra = $t3->formatLocalized('%d de %B de %Y');
                            }
    
                            if ($lote->fecha_carga) {
                                setlocale(LC_TIME, 'es_MX.utf8');
                                $tiempo2 = new Carbon($lote->fecha_carga);
                                $lote->fecha_carga = $tiempo2->formatLocalized('%d de %B de %Y');
                            }
    
                            if ($lote->fecha_revision) {
                                setlocale(LC_TIME, 'es_MX.utf8');
                                $tiempo = new Carbon($lote->fecha_revision);
                                $lote->fecha_revision = $tiempo->formatLocalized('%d de %B de %Y');
                            }
    
                            if ($lote->fecha_dtu) {
                                setlocale(LC_TIME, 'es_MX.utf8');
                                $tiempo = new Carbon($lote->fecha_dtu);
                                $lote->fecha_dtu = $tiempo->formatLocalized('%d de %B de %Y');
                            }
    
                            $sheet->row($index + 2, [
                                $lote->proyecto,
                                $lote->num_etapa,
                                $lote->manzana,
                                $lote->num_lote,
                                $lote->modelo,
                                $lote->paq_ruv,
                                $lote->fecha_siembra,
                                $lote->nombre.' '.$lote->apellidos,
                                $lote->fecha_carga,
                                $lote->num_cuv,
                                $lote->fecha_asignacion,
                                $lote->empresa,
                                $lote->fecha_revision,
                                $lote->fecha_dtu,

                                
                            ]);
                        }
                        $num = 'A1:N' . $cont;
                        $sheet->setBorder($num, 'thin');
                    });
                }
    
            )->download('xls');
    }
}
