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
        $criterio = $request->criterio;
        if($buscar=='')
        {
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
                      DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),'licencias.f_planos','licencias.f_ingreso','licencias.num_licencia','licencias.f_salida')
                      ->orderBy('fraccionamientos.nombre','DESC')
                      ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
        }
       else
        {
            if($criterio != 'arquitecto'){
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
                        'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida')
                        ->where($criterio, 'like', '%'. $buscar . '%')
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
                            'licencias.f_ingreso','licencias.num_licencia','licencias.f_salida')
                            ->where('personal.nombre', 'like', '%'. $buscar . '%')
                            ->orWhere('personal.apellidos', 'like', '%'. $buscar . '%')
                            ->orderBy('fraccionamientos.nombre','DESC')
                            ->orderBy('lotes.etapa_servicios','DESC')->paginate(5);
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
    public function update(Request $request)
    {
       if(!$request->ajax())return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $licencia = Licencia::findOrFail($request->id);
        $licencia->f_planos=$request->f_planos;
        $licencia->f_ingreso=$request->f_ingreso;
        $licencia->f_salida=$request->f_salida;
        $licencia->num_licencia=$request->num_licencia;
        

        $licencia->save();
    }
}
