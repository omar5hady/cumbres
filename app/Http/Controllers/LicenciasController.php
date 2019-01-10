<?php

namespace App\Http\Controllers;
use App\Lote;
use App\Modelo;
use App\Etapa;
use App\Licencia;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;

class LicenciasController extends Controller
{
    public function index(Request $request) //Index para modulo de licencias
    {
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_arquitecto = $request->b_arquitecto;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $b_modelo = $request->b_modelo;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('personal','lotes.arquitecto_id','=','personal.id')
            ->join('personal as p','licencias.perito_dro','=','p.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                      'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                      DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                      DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),'licencias.f_planos',
                      'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                      'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                      'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                      ->orderBy('licencias.cambios','DESC')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.manzana','ASC')
                      ->orderBy('lotes.num_lote','ASC')->paginate(5);
        }
       else
        {
            if($criterio != 'arquitecto' && $criterio != 'lotes.fraccionamiento_id' ){
                $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->join('personal','lotes.arquitecto_id','=','personal.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
            }
            if($criterio == 'licencias.f_planos'){
                $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->join('personal','lotes.arquitecto_id','=','personal.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
            }
            else{
            if($criterio == 'lotes.fraccionamiento_id' ){
                if($b_manzana != ''){
                    $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->join('personal','lotes.arquitecto_id','=','personal.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                            'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                            'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                            'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                        ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', 'like', '%'. $b_lote . '%')
                            ->where('modelos.nombre', 'like', '%'. $b_modelo . '%')
                            ->where('personal.nombre', 'like', '%'. $b_arquitecto . '%')
                            ->orderBy('licencias.cambios','DESC')
                            ->orderBy('fraccionamientos.nombre','DESC')
                            ->orderBy('lotes.manzana','ASC')
                            ->orderBy('lotes.num_lote','ASC')->paginate(5);
                        }
                        else{
                            $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                ->join('licencias','lotes.id','=','licencias.id')
                                ->join('personal','lotes.arquitecto_id','=','personal.id')
                                ->join('etapas','lotes.etapa_id','=','etapas.id')
                                ->join('modelos','lotes.modelo_id','=','modelos.id')
                                ->join('empresas','lotes.empresa_id','=','empresas.id')
                                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                                        'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                                    ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                        ->where('lotes.num_lote', 'like', '%'. $b_lote . '%')
                                        ->where('modelos.nombre', 'like', '%'. $b_modelo . '%')
                                        ->where('personal.nombre', 'like', '%'. $b_arquitecto . '%')
                                        ->orderBy('licencias.cambios','DESC')
                                        ->orderBy('fraccionamientos.nombre','DESC')
                                        ->orderBy('lotes.manzana','ASC')
                                        ->orderBy('lotes.num_lote','ASC')->paginate(5);

                        }
                    }
                    else{
                        $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                        ->join('licencias','lotes.id','=','licencias.id')
                        ->join('personal','lotes.arquitecto_id','=','personal.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('empresas','lotes.empresa_id','=','empresas.id')
                        ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                                'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                                'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                                'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                                'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                                'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                                'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                                'licencias.foto_lic','licencias.foto_predial','licencias.modelo_ant')
                                ->where('personal.nombre', 'like', '%'. $buscar . '%')
                                ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                                ->orderBy('licencias.cambios','DESC')
                                ->orderBy('fraccionamientos.nombre','DESC')
                                ->orderBy('lotes.manzana','ASC')
                                ->orderBy('lotes.num_lote','ASC')->paginate(5);
                }
            
            }
        }
 

        return [
            'pagination' => [
                'total'         => $licencias->total(),
                'current_page'  => $licencias->currentPage(),
                'per_page'      => $licencias->perPage(),
                'last_page'     => $licencias->lastPage(),
                'from'          => $licencias->firstItem(),
                'to'            => $licencias->lastItem(),
            ],
            'licencias' => $licencias
            
        ];    
    }


    public function indexActa(Request $request) //Index para modulo de licencias
    {
        if(!$request->ajax())return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        if($buscar=='')
        {
            $actas = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('personal','lotes.arquitecto_id','=','personal.id')
            ->join('personal as p','licencias.perito_dro','=','p.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('empresas','lotes.empresa_id','=','empresas.id')
            ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                      'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                      'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                      'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                      'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                      DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                      DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),'licencias.f_planos',
                      'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                      'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                      'licencias.avance','licencias.term_ingreso','licencias.term_salida','licencias.num_acta',
                      'licencias.foto_acta')
                      ->orderBy('licencias.cambios','DESC')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.manzana','ASC')
                      ->orderBy('lotes.num_lote','ASC')->paginate(5);
        }
       else
        {
            if($criterio != 'lotes.fraccionamiento_id' ){
                $actas = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->join('personal','lotes.arquitecto_id','=','personal.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                        DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.avance','licencias.term_ingreso','licencias.term_salida','licencias.num_acta',
                        'licencias.foto_acta')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
            }
            if($criterio == 'licencias.term_ingreso' || $criterio == 'licencias.term_salida'){
                $actas = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                ->join('licencias','lotes.id','=','licencias.id')
                ->join('personal','lotes.arquitecto_id','=','personal.id')
                ->join('etapas','lotes.etapa_id','=','etapas.id')
                ->join('modelos','lotes.modelo_id','=','modelos.id')
                ->join('empresas','lotes.empresa_id','=','empresas.id')
                ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                        'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                        'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                        'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                        'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                        DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.avance','licencias.term_ingreso','licencias.term_salida','licencias.num_acta',
                        'licencias.foto_acta')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.manzana','ASC')
                        ->orderBy('lotes.num_lote','ASC')->paginate(5);
            }else{
                if($criterio == 'lotes.fraccionamiento_id' ){
                    $actas = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->join('licencias','lotes.id','=','licencias.id')
                    ->join('personal','lotes.arquitecto_id','=','personal.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('modelos','lotes.modelo_id','=','modelos.id')
                    ->join('empresas','lotes.empresa_id','=','empresas.id')
                    ->select('fraccionamientos.nombre as proyecto','etapas.num_etapa as etapas','lotes.manzana','lotes.num_lote','lotes.sublote',
                            'modelos.nombre as modelo','empresas.nombre as empresa', 'lotes.calle','lotes.numero','lotes.interior','lotes.terreno',
                            'lotes.construccion','lotes.casa_muestra','lotes.lote_comercial','lotes.id',
                            'lotes.fraccionamiento_id','lotes.etapa_id', 'lotes.modelo_id','lotes.comentarios',
                            'lotes.clv_catastral','lotes.etapa_servicios','lotes.credito_puente','lotes.siembra',
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                            DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),'licencias.f_planos',
                            'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                            'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.num_acta',
                            'licencias.foto_acta')
                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', 'like', '%'. $b_lote . '%')
                            ->orderBy('licencias.cambios','DESC')
                            ->orderBy('fraccionamientos.nombre','DESC')
                            ->orderBy('lotes.manzana','ASC')
                            ->orderBy('lotes.num_lote','ASC')->paginate(5);
                    }
            }
        }
 

        return [
            'pagination' => [
                'total'         => $actas->total(),
                'current_page'  => $actas->currentPage(),
                'per_page'      => $actas->perPage(),
                'last_page'     => $actas->lastPage(),
                'from'          => $actas->firstItem(),
                'to'            => $actas->lastItem(),
            ],
            'actas' => $actas
            
        ];    
    
    }

    public function update(Request $request)
    {
       if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $licencia = Licencia::findOrFail($request->id);
        $numLicencia=$licencia->num_licencia;
        $salida=$licencia->f_salida;
        $licencia->f_planos=$request->f_planos;
        $licencia->f_ingreso=$request->f_ingreso;
        $licencia->f_salida=$request->f_salida;
        $licencia->num_licencia=$request->num_licencia;
        $licencia->perito_dro=$request->perito_dro;
        if($request->num_licencia!=''){
            $modeloAnt=$licencia->modelo_ant;
        
            if($request->num_licencia && $modeloAnt=='N/A' && $salida!=$request->f_salida)
                $licencia->cambios = 0;
            
            
            if($modeloAnt != 'N/A' &&  $salida!=$request->f_salida && $request->num_licencia!=''){
                $licencia->f_planos=NULL;
                $licencia->f_ingreso=NULL;
                $licencia->f_salida=NULL;
                $licencia->modelo_ant='N/A';
            }

            
        }

        $licencia->save();

        $lote = Lote::findOrFail($request->id);
        $lote->arquitecto_id=$request->arquitecto_id;

        

        $lote->save();
    }


    public function updateActas(Request $request)
    {
       if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $acta = Licencia::findOrFail($request->id);
       
        $acta->term_ingreso=$request->term_ingreso;
        $acta->term_salida=$request->term_salida;
        $acta->avance=$request->avance;
        $acta->num_acta=$request->num_acta;
       

        $acta->save();
    }

//funcion para exportar el resumen de licencias en excel
    public function exportExcel(Request $request)
    {
        $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('personal','lotes.arquitecto_id','=','personal.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.fraccionamiento_id',DB::raw('COUNT(lotes.fraccionamiento_id) as num_viviendas'),
            'fraccionamientos.nombre as proyecto','lotes.credito_puente','lotes.siembra','licencias.f_planos',
            'licencias.f_ingreso','licencias.f_salida',DB::raw("SUM(licencias.avance) as prom_avance"),
             DB::raw('MONTH(lotes.fecha_ini) month'),'lotes.ehl_solicitado','personal.nombre as arquitecto')
            ->where('lotes.siembra','!=','NULL')
            ->groupBy('lotes.fraccionamiento_id')
            ->groupBy('lotes.siembra')
            ->groupBy('licencias.f_planos')
            ->groupBy('licencias.f_ingreso')
            ->groupBy('personal.nombre')
            ->groupBy('licencias.f_salida')
            ->groupBy('lotes.credito_puente')
            ->groupBy('lotes.ehl_solicitado')
            ->groupBy('month')->distinct()->get();

            return Excel::create('resumen_licencias', function($excel) use ($licencias){
                $excel->sheet('licencias', function($sheet) use ($licencias){
                    //$sheet->fromArray($licencias);
                    $sheet->row(1, [
                        'Fracc.', 'No. Viviendas', 'Credito Puente', 'EHL Solicitado', 'Mes para iniciar', 'Arquitecto',
                        'Siembra', 'Planos', 'Ingreso','Salida','Avance'
                    ]);


                    $sheet->cells('A1:K1', function ($cells) {
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

                    
                    $cont=1;

                    foreach($licencias as $index => $licencia) {
                        $cont++;
                        if($licencia->prom_avance>0)
                                $avance=$licencia->prom_avance / $licencia->num_viviendas;
                            else
                                $avance=0;
                        
                        if($licencia->siembra){
                            setlocale(LC_TIME, 'es');
                            $tiempo = new Carbon($licencia->siembra);
                            $licencia->siembra = $tiempo->formatLocalized('%d de %B de %Y');
                            
                        }

                        if($licencia->f_planos){
                            setlocale(LC_TIME, 'es');
                            $tiempo2 = new Carbon($licencia->f_planos);
                            $licencia->f_planos = $tiempo2->formatLocalized('%d de %B de %Y');
                            
                        }

                        if($licencia->f_ingreso){
                            setlocale(LC_TIME, 'es');
                            $tiempo = new Carbon($licencia->f_ingreso);
                            $licencia->f_ingreso = $tiempo->formatLocalized('%d de %B de %Y');
                            
                        }

                        if($licencia->f_salida){
                            setlocale(LC_TIME, 'es');
                            $tiempo = new Carbon($licencia->f_salida);
                            $licencia->f_salida = $tiempo->formatLocalized('%d de %B de %Y');
                            
                        }

                        switch($licencia->month){
                            case '1':
                                $mes = "Enero";
                            break;
                            case '2':
                                $mes = "Febrero";
                            break;
                            case '3':
                                $mes = "Marzo";
                            break;
                            case '4':
                                $mes = "Abril";
                            break;
                            case '5':
                                $mes = "Mayo";
                            break;
                            case '6':
                                $mes = "Junio";
                            break;
                            case '7':
                                $mes = "Julio";
                            break;
                            case '8':
                                $mes = "Agosto";
                            break;
                            case '9':
                                $mes = "Septiembre";
                            break;
                            case '10':
                                $mes = "Octubre";
                            break;
                            case '11':
                                $mes = "Noviembre";
                            break;
                            case '12':
                                $mes = "Diciembre";
                            break;
                            default:
                                $mes = "";
                        }
                        $sheet->row($index+2, [
                            $licencia->proyecto, 
                            $licencia->num_viviendas, 
                            $licencia->credito_puente, 
                            $licencia->ehl_solicitado, 
                            $mes,
                            $licencia->arquitecto,
                            $licencia->siembra,
                            $licencia->f_planos,
                            $licencia->f_ingreso,
                            $licencia->f_salida,
                            $avance
                        ]);	
                    }
                    $num='A1:K' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
            
            )->download('xls');
        
      
    }


    //funciones para carga y descarga de la licencia

    public function formSubmit(Request $request, $id)
    {

        $fileName = time().'.'.$request->foto_lic->getClientOriginalExtension();
        $moved =  $request->foto_lic->move(public_path('/files/licencias'), $fileName);

        if($moved){
            if(!$request->ajax())return redirect('/');
            $licencias = Licencia::findOrFail($request->id);
            $licencias->foto_lic = $fileName;
            $licencias->id = $id;
            $licencias->save(); //Insert
    
            }
        
    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function downloadFile($fileName){
        
        $pathtoFile = public_path().'/files/licencias/'.$fileName;
        return response()->download($pathtoFile);
    }


    //funciones para carga y descarga de la acta

    public function formSubmitActa(Request $request, $id)
    {

        $fileName = time().'.'.$request->foto_acta->getClientOriginalExtension();
        $moved =  $request->foto_acta->move(public_path('/files/actas'), $fileName);

        if($moved){
            if(!$request->ajax())return redirect('/');
            $actas = Licencia::findOrFail($request->id);
            $actas->foto_acta = $fileName;
            $actas->id = $id;
            $actas->save(); //Insert
    
            }
        
    	return response()->json(['success'=>'You have successfully upload file.']);
    }

    public function downloadFileActa($fileName){
        
        $pathtoFile = public_path().'/files/actas/'.$fileName;
        return response()->download($pathtoFile);
    }

        //funciones para carga y descarga de predial

        public function formSubmitPredial(Request $request, $id)
        {
    
            $fileName = time().'.'.$request->foto_predial->getClientOriginalExtension();
            $moved =  $request->foto_predial->move(public_path('/files/prediales'), $fileName);
    
            if($moved){
                if(!$request->ajax())return redirect('/');
                $predial = Licencia::findOrFail($request->id);
                $predial->foto_predial = $fileName;
                $predial->id = $id;
                $predial->save(); //Insert
        
                }
            
            return response()->json(['success'=>'You have successfully upload file.']);
        }
    
        public function downloadFilePredial($fileName){
            
            $pathtoFile = public_path().'/files/prediales/'.$fileName;
            return response()->download($pathtoFile);
        }
    



}
