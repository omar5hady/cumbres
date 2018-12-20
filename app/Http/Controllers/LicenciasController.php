<?php

namespace App\Http\Controllers;
use App\Lote;
use App\Modelo;
use App\Etapa;
use App\Licencia;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LicenciasController extends Controller
{
    public function index(Request $request)
    {
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
                      'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.foto_lic')
                      ->orderBy('licencias.cambios','DESC')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
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
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.foto_lic')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
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
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.foto_lic')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')
                        ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
            }
            else{
            if($criterio == 'lotes.fraccionamiento_id' ){
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
                            'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.foto_lic')
                        ->where('lotes.fraccionamiento_id', '=',  $buscar)
                        ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', 'like', '%'. $b_lote . '%')
                            ->where('modelos.nombre', 'like', '%'. $b_modelo . '%')
                            ->where('personal.nombre', 'like', '%'. $b_arquitecto . '%')
                            ->orderBy('licencias.cambios','DESC')
                            ->orderBy('fraccionamientos.nombre','DESC')
                            ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
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
                                'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios','licencias.foto_lic')
                                ->where('personal.nombre', 'like', '%'. $buscar . '%')
                                ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                                ->orderBy('licencias.cambios','DESC')
                                ->orderBy('fraccionamientos.nombre','DESC')
                                ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
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


    public function indexActa(Request $request)
    {
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
                      'licencias.avance','licencias.term_ingreso','licencias.term_salida')
                      ->orderBy('licencias.cambios','DESC')
                      ->orderBy('fraccionamientos.nombre','DESC')->paginate(5);
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
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.avance','licencias.term_ingreso','licencias.term_salida')
                        ->where($criterio, 'like', '%'. $buscar . '%')
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')->paginate(5);
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
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                        'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios',
                        'licencias.avance','licencias.term_ingreso','licencias.term_salida')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->orderBy('licencias.cambios','DESC')
                        ->orderBy('fraccionamientos.nombre','DESC')->paginate(5);
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
                            DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos',
                            'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida','lotes.arquitecto_id',
                            'licencias.perito_dro','fraccionamientos.nombre as fraccionamiento','licencias.cambios')
                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                            ->where('lotes.manzana', '=', $b_manzana)
                            ->where('lotes.num_lote', 'like', '%'. $b_lote . '%')
                            ->orderBy('licencias.cambios','DESC')
                            ->orderBy('fraccionamientos.nombre','DESC')->paginate(5);
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
        $licencia->f_planos=$request->f_planos;
        $licencia->f_ingreso=$request->f_ingreso;
        $licencia->f_salida=$request->f_salida;
        $licencia->num_licencia=$request->num_licencia;
        $licencia->perito_dro=$request->perito_dro;
        $licencia->cambios = 0;

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
       

        $acta->save();
    }


    public function resumeLicencias(Request $request)
    {
        $licencias = Lote::join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->join('licencias','lotes.id','=','licencias.id')
            ->join('personal','lotes.arquitecto_id','=','personal.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->select('lotes.fraccionamiento_id',DB::raw('COUNT(lotes.fraccionamiento_id) as num_viviendas'),
            'fraccionamientos.nombre','lotes.credito_puente','lotes.siembra','licencias.f_planos',
            'licencias.f_ingreso','licencias.f_salida','licencias.avance')
            ->where('lotes.siembra','!=','NULL')
            ->groupBy('lotes.fraccionamiento_id')
            ->groupBy('lotes.siembra')
            ->groupBy('licencias.f_planos')
            ->groupBy('licencias.f_ingreso')
            ->groupBy('licencias.f_salida')
            ->groupBy('licencias.avance')
            ->groupBy('lotes.credito_puente')->distinct()->get();
        
        

        return [
            'licencias' => $licencias
        ];    
    }


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

  



}
