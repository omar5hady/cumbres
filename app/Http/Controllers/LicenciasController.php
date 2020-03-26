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
use File;
use Auth;

class LicenciasController extends Controller
{
    public function index(Request $request) //Index para modulo de licencias
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_arquitecto = $request->b_arquitecto;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $b_modelo = $request->b_modelo;
        $criterio = $request->criterio;
        $b_puente = $request->b_puente;
        $b_num_inicio = $request->b_num_inicio;

        $query = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
                ->join('licencias', 'lotes.id', '=', 'licencias.id')
                ->join('personal', 'lotes.arquitecto_id', '=', 'personal.id')
                ->join('personal as p', 'licencias.perito_dro', '=', 'p.id')
                ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
                ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
                ->join('empresas', 'lotes.empresa_id', '=', 'empresas.id')
                ->select(
                    'fraccionamientos.nombre as proyecto',
                    'etapas.num_etapa as etapas',
                    'lotes.manzana',
                    'lotes.num_lote',
                    'lotes.sublote',
                    'modelos.nombre as modelo',
                    'empresas.nombre as empresa',
                    'modelos.archivo',
                    'lotes.calle',
                    'lotes.numero',
                    'lotes.interior',
                    'lotes.terreno',
                    'lotes.construccion',
                    'lotes.casa_muestra',
                    'lotes.lote_comercial',
                    'lotes.id',
                    'lotes.fraccionamiento_id',
                    'lotes.etapa_id',
                    'lotes.modelo_id',
                    'lotes.comentarios',
                    'lotes.clv_catastral',
                    'lotes.etapa_servicios',
                    'lotes.credito_puente',
                    'lotes.siembra',
                    'lotes.num_inicio',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                    DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),
                    'licencias.f_planos',
                    'licencias.f_planos_obra',
                    'licencias.f_ingreso',
                    'licencias.num_licencia',
                    'licencias.f_salida',
                    'lotes.arquitecto_id',
                    'licencias.perito_dro',
                    'fraccionamientos.nombre as fraccionamiento',
                    'licencias.cambios',
                    'licencias.foto_lic',
                    'licencias.foto_predial',
                    'licencias.modelo_ant',
                    'licencias.id as licenciasid'
                );

        if ($buscar == '') {
            $licencias = $query
                ->orderBy('licencias.cambios', 'DESC')
                ->orderBy('fraccionamientos.nombre', 'DESC')
                ->orderBy('lotes.manzana', 'ASC')
                ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
        } else {
            if ($criterio != 'arquitecto' && $criterio != 'lotes.fraccionamiento_id' && $criterio != 'licencias.perito_dro' && $criterio != 'licencias.f_planos') {
                $licencias = $query
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('licencias.cambios', 'DESC')
                    ->orderBy('fraccionamientos.nombre', 'DESC')
                    ->orderBy('lotes.manzana', 'ASC')
                    ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
            } else {

                if ($criterio == 'lotes.siembra') {
                    $licencias = $query
                        ->where($criterio, '=', $buscar)
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                } 
                elseif ($criterio == 'licencias.perito_dro'){
                    $licencias = $query
                        ->where($criterio, '=', $buscar)
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                }else {
                    if ($criterio == 'licencias.f_planos') {
                        $licencias = $query
                            ->whereBetween($criterio, [$buscar, $buscar2])
                            ->orderBy('licencias.cambios', 'DESC')
                            ->orderBy('fraccionamientos.nombre', 'DESC')
                            ->orderBy('lotes.manzana', 'ASC')
                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                    } else {

                        if ($criterio == 'lotes.fraccionamiento_id') {
                            if($b_puente == ''){
                                if($b_num_inicio == ''){
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                else{
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                
                            }
                            else{
                                if($b_num_inicio == ''){
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                else{
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                
                            }
                            
                        } else {
                            $licencias = $query
                                ->where('personal.nombre', 'like', '%' . $buscar . '%')
                                ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                        }
                    }
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
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $b_puente = $request->b_puente;
        $criterio = $request->criterio;
        $b_num_inicio = $request->b_num_inicio;

        $query = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('personal', 'lotes.arquitecto_id', '=', 'personal.id')
            ->join('personal as p', 'licencias.perito_dro', '=', 'p.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->join('empresas', 'lotes.empresa_id', '=', 'empresas.id')
            ->select(
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa as etapas',
                'lotes.manzana',
                'lotes.num_lote',
                'lotes.sublote',
                'modelos.nombre as modelo',
                'empresas.nombre as empresa',
                'lotes.calle',
                'lotes.numero',
                'lotes.credito_puente',
                'lotes.interior',
                'lotes.terreno',
                'lotes.construccion',
                'lotes.casa_muestra',
                'lotes.lote_comercial',
                'lotes.id',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.modelo_id',
                'lotes.comentarios',
                'lotes.clv_catastral',
                'lotes.etapa_servicios',
                'lotes.credito_puente',
                'lotes.siembra',
                'lotes.num_inicio',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),
                'licencias.f_planos',
                'licencias.f_ingreso',
                'licencias.num_licencia',
                'licencias.f_salida',
                'lotes.arquitecto_id',
                'licencias.perito_dro',
                'fraccionamientos.nombre as fraccionamiento',
                'licencias.cambios',
                'licencias.avance',
                'licencias.term_ingreso',
                'licencias.term_salida',
                'licencias.num_acta',
                'licencias.foto_acta'
            );

        if ($buscar == '') {
            $actas = $query
                ->orderBy('licencias.cambios', 'DESC')
                ->orderBy('fraccionamientos.nombre', 'DESC')
                ->orderBy('lotes.manzana', 'ASC')
                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id') {
                $actas = $query
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('licencias.cambios', 'DESC')
                    ->orderBy('fraccionamientos.nombre', 'DESC')
                    ->orderBy('lotes.manzana', 'ASC')
                    ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
            } else {
                if ($criterio == 'licencias.term_ingreso' || $criterio == 'licencias.term_salida') {
                    $actas = $query
                        ->whereBetween($criterio, [$buscar, $buscar2])
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id') {
                        if($b_num_inicio == ''){
                            if($b_puente == ''){
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                            }
                            else{
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.credito_puente','=',$b_puente)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
    
                            }
                        }
                        else{
                            if($b_puente == ''){
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.num_inicio', '=',  $b_num_inicio)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                            }
                            else{
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.num_inicio', '=',  $b_num_inicio)
                                ->where('lotes.credito_puente','=',$b_puente)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
    
                            }
                        }
                        
                        
                    }
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

    public function indexVisita(Request $request) //Index para modulo de licencias
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $b_etapa = $request->b_etapa;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;

        $query = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->select(
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa',
                'lotes.manzana',
                'lotes.num_lote',
                'lotes.sublote',
                'modelos.nombre as modelo',
                'lotes.calle',
                'lotes.numero',
                'lotes.interior',
                'lotes.id',
                'lotes.paquete',
                'licencias.avance',
                'licencias.visita_avaluo',
                'licencias.f_planos_obra',
                'lotes.contrato',
                'lotes.firmado',
                'lotes.fecha_fin'
            );

        if ($buscar == '') {
            $lotes = $query
                ->where('lotes.aviso','!=','0')
                ->orderBy('fraccionamientos.nombre', 'ASC')
                ->orderBy('etapas.num_etapa', 'ASC')
                ->orderBy('lotes.manzana', 'ASC')
                ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
        }
        else{
            switch($criterio){
                case 'lotes.fraccionamiento_id':{
                    if($b_etapa=='' && $b_lote=='' && $b_manzana==''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa!='' && $b_lote=='' && $b_manzana==''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('etapas.id','=',$b_etapa)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa!='' && $b_lote=='' && $b_manzana!=''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('etapas.id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa!='' && $b_lote!='' && $b_manzana!=''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('etapas.id','=',$b_etapa)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa=='' && $b_lote=='' && $b_manzana!=''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa=='' && $b_lote!='' && $b_manzana!=''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.manzana','=',$b_manzana)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa=='' && $b_lote!='' && $b_manzana==''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }
                    elseif($b_etapa!='' && $b_lote!='' && $b_manzana==''){
                        $lotes = $query
                        ->where('lotes.aviso','!=','0')
                        ->where('lotes.fraccionamiento_id','=',$buscar)
                        ->where('etapas.id','=',$b_etapa)
                        ->where('lotes.num_lote','=',$b_lote)
                        ->orderBy('fraccionamientos.nombre', 'ASC')
                        ->orderBy('etapas.num_etapa', 'ASC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    }

                    break;
                }
                default:{
                    $lotes = $query
                    ->where('lotes.aviso','!=','0')
                    ->where($criterio,'=',$buscar)
                    ->orderBy('fraccionamientos.nombre', 'ASC')
                    ->orderBy('etapas.num_etapa', 'ASC')
                    ->orderBy('lotes.manzana', 'ASC')
                    ->orderBy('lotes.num_lote', 'ASC')->paginate(8);
                    break;
                }
            }

        }


        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes

        ];
    }

    public function updateMasa(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $licencia = Licencia::findOrFail($request->id);
        $licencia->f_planos_obra = $request->f_planos_obra;
        $licencia->perito_dro = $request->perito_dro;
        $licencia->save();
    }


    public function updateMasaLicencias(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $licencia = Licencia::findOrFail($request->id);
        $licencia->f_ingreso = $request->f_ingreso;
        $licencia->f_salida = $request->f_salida;
        $licencia->num_licencia = $request->num_licencia;
        $licencia->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $licencia = Licencia::findOrFail($request->id);
        $numLicencia = $licencia->num_licencia;
        $salida = $licencia->f_salida;
        $licencia->f_planos_obra = $request->f_planos_obra;
        $licencia->f_planos = $request->f_planos;
        $licencia->f_ingreso = $request->f_ingreso;
        $licencia->f_salida = $request->f_salida;
        $licencia->num_licencia = $request->num_licencia;
        $licencia->perito_dro = $request->perito_dro;
        if ($request->num_licencia != '') {
            $modeloAnt = $licencia->modelo_ant;

            if ($request->num_licencia && $modeloAnt == 'N/A' && $salida != $request->f_salida)
                $licencia->cambios = 0;


            if ($modeloAnt != 'N/A' &&  $salida != $request->f_salida && $request->num_licencia != '') {
                $licencia->f_planos = NULL;
                $licencia->f_ingreso = NULL;
                $licencia->f_salida = NULL;
                $licencia->modelo_ant = 'N/A';
            }
        }

        $licencia->save();

        $lote = Lote::findOrFail($request->id);
        $lote->arquitecto_id = $request->arquitecto_id;



        $lote->save();
    }

    public function AsigFechaVisita(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11 || Auth::user()->rol_id == 9)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $licencia = Licencia::findOrFail($request->id);
        $licencia->visita_avaluo = $request->visita_avaluo;
        
        $licencia->save();

    }


    public function updateActas(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        //FindOrFail se utiliza para buscar lo que recibe de argumento
        $acta = Licencia::findOrFail($request->id);

        $acta->term_ingreso = $request->term_ingreso;
        $acta->term_salida = $request->term_salida;
        $acta->num_acta = $request->num_acta;


        $acta->save();
    }

    public function updateMasaActas(Request $request)
    {

        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $acta = Licencia::findOrFail($request->id);
        $acta->term_ingreso = $request->term_ingreso;
        $acta->term_salida = $request->term_salida;
        $acta->num_acta = $request->num_acta;
        $acta->save();
    }

    //funcion para exportar el resumen de licencias en excel
    public function exportExcel(Request $request)
    {
        $licencias = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('personal', 'lotes.arquitecto_id', '=', 'personal.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->select(
                'lotes.fraccionamiento_id',
                DB::raw('COUNT(lotes.fraccionamiento_id) as num_viviendas'),
                'fraccionamientos.nombre as proyecto',
                'lotes.credito_puente',
                'lotes.siembra',
                'licencias.f_planos',
                'licencias.f_planos_obra',
                'licencias.f_ingreso',
                'licencias.f_salida',
                DB::raw("SUM(licencias.avance) as prom_avance"),
                DB::raw('MONTH(lotes.fecha_ini) month'),
                DB::raw('DATEDIFF(licencias.f_planos,lotes.ehl_solicitado) as diasPlanos'),
                DB::raw('DATEDIFF(licencias.f_salida,lotes.ehl_solicitado) as diasSalida'),
                DB::raw('(CASE WHEN licencias.num_acta IS NULL THEN 0 ELSE 1 END) as acta'),
                'lotes.ehl_solicitado',
                'personal.nombre as arquitecto'
            )
            ->where('lotes.siembra', '!=', 'NULL')
            ->groupBy('lotes.fraccionamiento_id')
            ->groupBy('lotes.siembra')
            ->groupBy('licencias.f_planos_obra')
            ->groupBy('licencias.f_planos')
            ->groupBy('licencias.f_ingreso')
            ->groupBy('personal.nombre')
            ->groupBy('licencias.f_salida')
            ->groupBy('lotes.credito_puente')
            ->groupBy('lotes.ehl_solicitado')
            ->groupBy('month')
            ->groupBy('acta')
            ->distinct()->get();
        // return [

        //     'licencias' => $licencias

        // ];    

        return Excel::create(
            'resumen_licencias',
            function ($excel) use ($licencias) {
                $excel->sheet('licencias', function ($sheet) use ($licencias) {

                    $sheet->row(1, [
                        'Fracc.', 'No. Viviendas', 'Credito Puente', 'EHL Solicitado', 'Mes para iniciar', 'Arquitecto',
                        'Siembra', 'Planos obra', 'Planos licencia', 'Ingreso', 'Salida', 'Avance', 'Acta de terminacion'
                    ]);


                    $sheet->cells('A1:M1', function ($cells) {
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

                    foreach ($licencias as $index => $licencia) {
                        $cont++;
                        if ($licencia->prom_avance > 0)
                            $avance = $licencia->prom_avance / $licencia->num_viviendas;
                        else
                            $avance = 0;

                        if ($licencia->siembra) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->siembra);
                            $licencia->siembra = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->ehl_solicitado) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $t1 = new Carbon($licencia->ehl_solicitado);
                            $licencia->ehl_solicitado = $t1->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_planos_obra) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $t3 = new Carbon($licencia->f_planos_obra);
                            $licencia->f_planos_obra = $t3->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_planos) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo2 = new Carbon($licencia->f_planos);
                            $licencia->f_planos = $tiempo2->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_ingreso) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->f_ingreso);
                            $licencia->f_ingreso = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_salida) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->f_salida);
                            $licencia->f_salida = $tiempo->formatLocalized('%d de %B de %Y');
                        }


                        if ($licencia->diasPlanos > 15) {
                            $sheet->cell('H' . $cont, function ($cell) {
                                $cell->setBackground('#ff0000');
                                $cell->setFontColor('#ffffff');
                                // Set font family
                                $cell->setFontFamily('Calibri');

                                // Set font size
                                $cell->setFontSize(11);

                                // Set font weight to bold
                                $cell->setFontWeight('bold');
                            });
                        }


                        if ($licencia->diasSalida > 60) {
                            $sheet->cell('J' . $cont, function ($cell) {
                                $cell->setBackground('#ff0000');
                                $cell->setFontColor('#ffffff');
                                // Set font family
                                $cell->setFontFamily('Calibri');

                                // Set font size
                                $cell->setFontSize(11);

                                // Set font weight to bold
                                $cell->setFontWeight('bold');
                            });
                        }

                        if ($licencia->acta == 1) {

                            $completado = "Completado";
                        } else {
                            $completado = "";
                        }

                        switch ($licencia->month) {
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
                        $sheet->row($index + 2, [
                            $licencia->proyecto,
                            $licencia->num_viviendas,
                            $licencia->credito_puente,
                            $licencia->ehl_solicitado,
                            $mes,
                            $licencia->arquitecto,
                            $licencia->siembra,
                            $licencia->f_planos_obra,
                            $licencia->f_planos,
                            $licencia->f_ingreso,
                            $licencia->f_salida,
                            $avance,
                            $completado
                        ]);
                    }
                    $num = 'A1:M' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');
    }


    //funciones para carga y descarga de la licencia

    public function formSubmit(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $licenciaAnterior = Licencia::select('foto_lic', 'id')
            ->where('id', '=', $id)
            ->get();

        if ($licenciaAnterior->isEmpty() == 1) {
            $fileName = uniqid() . '.' . $request->foto_lic->getClientOriginalExtension();
            $moved =  $request->foto_lic->move(public_path('/files/licencias'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $licencias = Licencia::findOrFail($request->id);
                $licencias->foto_lic = $fileName;
                $licencias->id = $id;
                $licencias->fecha_licencia = $fecha;
                $licencias->save(); //Insert

            }
            return back();
        } else {
            $pathAnterior = public_path() . '/files/licencias/' . $licenciaAnterior[0]->foto_lic;
            File::delete($pathAnterior);

            $fileName = uniqid() . '.' . $request->foto_lic->getClientOriginalExtension();
            $moved =  $request->foto_lic->move(public_path('/files/licencias'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $licencias = Licencia::findOrFail($request->id);
                $licencias->foto_lic = $fileName;
                $licencias->id = $id;
                $licencias->fecha_licencia = $fecha;
                $licencias->save(); //Insert

            }
            return back();
        }
    }

    public function downloadFile($fileName)
    {

        $pathtoFile = public_path() . '/files/licencias/' . $fileName;
        return response()->download($pathtoFile);
    }


    //funciones para carga y descarga de la acta

    public function formSubmitActa(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();
        $actaAnterior = Licencia::select('foto_acta', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($actaAnterior->isEmpty() == 1) {
            $fileName = uniqid() . time() . '.' . $request->foto_acta->getClientOriginalExtension();
            $moved =  $request->foto_acta->move(public_path('/files/actas'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $actas = Licencia::findOrFail($request->id);
                $actas->foto_acta = $fileName;
                $actas->id = $id;
                $actas->fecha_acta = $fecha;
                $actas->save(); //Insert

            }

            return back();
        } else {
            $pathAnterior = public_path() . '/files/actas/' . $actaAnterior[0]->foto_acta;
            File::delete($pathAnterior);

            $fileName = uniqid() . time() . '.' . $request->foto_acta->getClientOriginalExtension();
            $moved =  $request->foto_acta->move(public_path('/files/actas'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $actas = Licencia::findOrFail($request->id);
                $actas->foto_acta = $fileName;
                $actas->id = $id;
                $actas->fecha_acta = $fecha;
                $actas->save(); //Insert

            }

            return back();
        }
    }

    public function downloadFileActa($fileName)
    {

        $pathtoFile = public_path() . '/files/actas/' . $fileName;
        return response()->download($pathtoFile);
    }

    //funciones para carga y descarga de predial

    public function formSubmitPredial(Request $request, $id)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $predialAnterior = Licencia::select('foto_predial', 'id')
            ->where('id', '=', $id)
            ->get();
        if ($predialAnterior->isEmpty() == 1) {
            $fileName = uniqid() . time() . '.' . $request->foto_predial->getClientOriginalExtension();
            $moved =  $request->foto_predial->move(public_path('/files/prediales'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Licencia::findOrFail($request->id);
                $predial->foto_predial = $fileName;
                $predial->id = $id;
                $predial->fecha_predial = $fecha;
                $predial->save(); //Insert

            }

            return back();
        } else {
            $pathAnterior = public_path() . '/files/prediales/' . $predialAnterior[0]->foto_predial;
            File::delete($pathAnterior);

            $fileName = uniqid() . time() . '.' . $request->foto_predial->getClientOriginalExtension();
            $moved =  $request->foto_predial->move(public_path('/files/prediales'), $fileName);

            if ($moved) {
                if (!$request->ajax()) return redirect('/');
                $predial = Licencia::findOrFail($request->id);
                $predial->foto_predial = $fileName;
                $predial->id = $id;
                $predial->fecha_predial = $fecha;
                $predial->save(); //Insert

            }

            return back();
        }
    }

    public function downloadFilePredial($fileName)
    {

        $pathtoFile = public_path() . '/files/prediales/' . $fileName;
        return response()->download($pathtoFile);
    }


    public function exportExcelLicencias(Request $request)
    {
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_arquitecto = $request->b_arquitecto;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $b_modelo = $request->b_modelo;
        $criterio = $request->criterio;
        $b_num_inicio = $request->b_num_inicio;
        $b_puente = $request->b_puente;

        $query = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('personal', 'lotes.arquitecto_id', '=', 'personal.id')
            ->join('personal as p', 'licencias.perito_dro', '=', 'p.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->join('empresas', 'lotes.empresa_id', '=', 'empresas.id')
            ->select(
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa as etapas',
                'lotes.manzana',
                'lotes.num_lote',
                'lotes.sublote',
                'modelos.nombre as modelo',
                'empresas.nombre as empresa',
                'lotes.calle',
                'lotes.numero',
                'lotes.interior',
                'lotes.terreno',
                'lotes.construccion',
                'lotes.casa_muestra',
                'lotes.lote_comercial',
                'lotes.id',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.modelo_id',
                'lotes.comentarios',
                'lotes.clv_catastral',
                'lotes.etapa_servicios',
                'lotes.credito_puente',
                'lotes.siembra',
                'lotes.num_inicio',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),
                'licencias.f_planos',
                'licencias.f_planos_obra',
                'licencias.f_ingreso',
                'licencias.num_licencia',
                'licencias.f_salida',
                'lotes.arquitecto_id',
                'licencias.perito_dro',
                'fraccionamientos.nombre as fraccionamiento',
                'licencias.cambios',
                'licencias.foto_lic',
                'licencias.foto_predial',
                'licencias.modelo_ant'
            );

        if ($buscar == '') {
            $licencias = $query
                ->orderBy('licencias.cambios', 'DESC')
                ->orderBy('fraccionamientos.nombre', 'DESC')
                ->orderBy('lotes.manzana', 'ASC')
                ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
        } else {
            if ($criterio != 'arquitecto' && $criterio != 'lotes.fraccionamiento_id' && $criterio != 'licencias.perito_dro' && $criterio != 'licencias.f_planos') {
                $licencias = $query
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('licencias.cambios', 'DESC')
                    ->orderBy('fraccionamientos.nombre', 'DESC')
                    ->orderBy('lotes.manzana', 'ASC')
                    ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
            } else {

                if ($criterio == 'lotes.siembra') {
                    $licencias = $query
                        ->where($criterio, '=', $buscar)
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                } 
                elseif ($criterio == 'licencias.perito_dro'){
                    $licencias = $query
                        ->where($criterio, '=', $buscar)
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                }else {
                    if ($criterio == 'licencias.f_planos') {
                        $licencias = $query
                            ->whereBetween($criterio, [$buscar, $buscar2])
                            ->orderBy('licencias.cambios', 'DESC')
                            ->orderBy('fraccionamientos.nombre', 'DESC')
                            ->orderBy('lotes.manzana', 'ASC')
                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                    } else {

                        if ($criterio == 'lotes.fraccionamiento_id') {
                            if($b_puente == ''){
                                if($b_num_inicio == ''){
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                else{
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                
                            }
                            else{
                                if($b_num_inicio == ''){
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                else{
                                    if ($b_manzana != '') {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.manzana', '=', $b_manzana)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    } else {
                                        $licencias = $query
                                            ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                            ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                            ->where('lotes.credito_puente', '=', $b_puente)
                                            ->where('modelos.nombre', 'like', '%' . $b_modelo . '%')
                                            ->where('personal.nombre', 'like', '%' . $b_arquitecto . '%')
                                            ->where('lotes.num_inicio', '=', $b_num_inicio)
                                            ->orderBy('licencias.cambios', 'DESC')
                                            ->orderBy('fraccionamientos.nombre', 'DESC')
                                            ->orderBy('lotes.manzana', 'ASC')
                                            ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                                    }
                                }
                                
                            }
                            
                        } else {
                            $licencias = $query
                                ->where('personal.nombre', 'like', '%' . $buscar . '%')
                                ->orWhere('personal.apellidos', 'like', '%' . $buscar . '%')
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(16);
                        }
                    }
                }
            }
        }

        return Excel::create(
            'Licencias',
            function ($excel) use ($licencias) {
                $excel->sheet('licencias', function ($sheet) use ($licencias) {

                    $sheet->row(1, [
                        'Fracc.', 'Manzana', 'Lote', 'Terreno', 'Construccion', 'Modelo', 'Arquitecto',
                        'No. Inicio','Fecha Inicio', 'Siembra obra', 'Planos licencia', 'DRO', 'Ingreso', 'Salida', 'Num.Licencia', 'Credito puente'
                    ]);


                    $sheet->cells('A1:P1', function ($cells) {
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

                    foreach ($licencias as $index => $licencia) {
                        $cont++;
                        if ($licencia->prom_avance > 0)
                            $avance = $licencia->prom_avance / $licencia->num_viviendas;
                        else
                            $avance = 0;

                        if ($licencia->siembra) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->siembra);
                            $licencia->siembra = $tiempo->formatLocalized('%d de %B de %Y');
                        }


                        if ($licencia->f_planos_obra) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $t3 = new Carbon($licencia->f_planos_obra);
                            $licencia->f_planos_obra = $t3->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_planos) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo2 = new Carbon($licencia->f_planos);
                            $licencia->f_planos = $tiempo2->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_ingreso) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->f_ingreso);
                            $licencia->f_ingreso = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        if ($licencia->f_salida) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($licencia->f_salida);
                            $licencia->f_salida = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        $sheet->row($index + 2, [
                            $licencia->proyecto,
                            $licencia->manzana,
                            $licencia->num_lote,
                            $licencia->terreno,
                            $licencia->construccion,
                            $licencia->modelo,
                            $licencia->arquitecto,
                            $licencia->num_inicio,
                            $licencia->siembra,
                            $licencia->f_planos_obra,
                            $licencia->f_planos,
                            $licencia->perito,
                            $licencia->f_ingreso,
                            $licencia->f_salida,
                            $licencia->num_licencia,
                            $licencia->credito_puente
                        ]);
                    }
                    $num = 'A1:P' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');
    }

    public function exportExcelActaTerminacion(Request $request)
    {
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $b_lote = $request->b_lote;
        $b_manzana = $request->b_manzana;
        $criterio = $request->criterio;
        $b_num_inicio = $request->b_num_inicio;
        $b_puente = $request->b_puente;

        $query = Lote::join('fraccionamientos', 'lotes.fraccionamiento_id', '=', 'fraccionamientos.id')
            ->join('licencias', 'lotes.id', '=', 'licencias.id')
            ->join('personal', 'lotes.arquitecto_id', '=', 'personal.id')
            ->join('personal as p', 'licencias.perito_dro', '=', 'p.id')
            ->join('etapas', 'lotes.etapa_id', '=', 'etapas.id')
            ->join('modelos', 'lotes.modelo_id', '=', 'modelos.id')
            ->join('empresas', 'lotes.empresa_id', '=', 'empresas.id')
            ->select(
                'fraccionamientos.nombre as proyecto',
                'etapas.num_etapa as etapas',
                'lotes.manzana',
                'lotes.num_lote',
                'lotes.sublote',
                'modelos.nombre as modelo',
                'empresas.nombre as empresa',
                'lotes.calle',
                'lotes.numero',
                'lotes.interior',
                'lotes.terreno',
                'lotes.construccion',
                'lotes.casa_muestra',
                'lotes.lote_comercial',
                'lotes.id',
                'lotes.fraccionamiento_id',
                'lotes.etapa_id',
                'lotes.modelo_id',
                'lotes.comentarios',
                'lotes.clv_catastral',
                'lotes.etapa_servicios',
                'lotes.credito_puente',
                'lotes.siembra',
                'lotes.num_inicio',
                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS arquitecto"),
                DB::raw("CONCAT(p.nombre,' ',p.apellidos) AS perito"),
                'licencias.f_planos',
                'licencias.f_ingreso',
                'licencias.num_licencia',
                'licencias.f_salida',
                'lotes.arquitecto_id',
                'licencias.perito_dro',
                'fraccionamientos.nombre as fraccionamiento',
                'licencias.cambios',
                'licencias.avance',
                'licencias.term_ingreso',
                'licencias.term_salida',
                'licencias.num_acta',
                'licencias.foto_acta'
            );

        if ($buscar == '') {
            $actas = $query
                ->orderBy('licencias.cambios', 'DESC')
                ->orderBy('fraccionamientos.nombre', 'DESC')
                ->orderBy('lotes.manzana', 'ASC')
                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
        } else {
            if ($criterio != 'lotes.fraccionamiento_id') {
                $actas = $query
                    ->where($criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('licencias.cambios', 'DESC')
                    ->orderBy('fraccionamientos.nombre', 'DESC')
                    ->orderBy('lotes.manzana', 'ASC')
                    ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
            } else {
                if ($criterio == 'licencias.term_ingreso' || $criterio == 'licencias.term_salida') {
                    $actas = $query
                        ->whereBetween($criterio, [$buscar, $buscar2])
                        ->orderBy('licencias.cambios', 'DESC')
                        ->orderBy('fraccionamientos.nombre', 'DESC')
                        ->orderBy('lotes.manzana', 'ASC')
                        ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                } else {
                    if ($criterio == 'lotes.fraccionamiento_id') {
                        if($b_num_inicio == ''){
                            if($b_puente == ''){
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                            }
                            else{
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.credito_puente','=',$b_puente)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
    
                            }
                        }
                        else{
                            if($b_puente == ''){
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.num_inicio', '=',  $b_num_inicio)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
                            }
                            else{
                                $actas = $query
                                ->where('lotes.fraccionamiento_id', '=',  $buscar)
                                ->where('lotes.manzana', 'like', '%' . $b_manzana . '%')
                                ->where('lotes.num_lote', 'like', '%' . $b_lote . '%')
                                ->where('lotes.num_inicio', '=',  $b_num_inicio)
                                ->where('lotes.credito_puente','=',$b_puente)
                                ->orderBy('licencias.cambios', 'DESC')
                                ->orderBy('fraccionamientos.nombre', 'DESC')
                                ->orderBy('lotes.manzana', 'ASC')
                                ->orderBy('lotes.num_lote', 'ASC')->paginate(15);
    
                            }
                        }
                        
                        
                    }
                }
            }
        }

        return Excel::create(
            'actas',
            function ($excel) use ($actas) {
                $excel->sheet('actas', function ($sheet) use ($actas) {

                    $sheet->row(1, [
                        'Fracc.', 'Manzana', 'Lote', 'Terreno', 'Construccion', 'DRO', 'Modelo',
                        'No. Inicio', 'Fecha Inicio', 'Avance', 'Fecha ingreso', 'Fecha salida', 'Num. Acta', 'Credito puente'
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

                    foreach ($actas as $index => $acta) {
                        $cont++;


                        if ($acta->term_ingreso) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($acta->term_ingreso);
                            $acta->term_ingreso = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        if ($acta->term_salida) {
                            setlocale(LC_TIME, 'es_MX.utf8');
                            $tiempo = new Carbon($acta->term_salida);
                            $acta->term_salida = $tiempo->formatLocalized('%d de %B de %Y');
                        }

                        $sheet->row($index + 2, [
                            $acta->proyecto,
                            $acta->manzana,
                            $acta->num_lote,
                            $acta->terreno,
                            $acta->construccion,
                            $acta->perito,
                            $acta->modelo,
                            $acta->num_inicio,
                            $acta->siembra,
                            $acta->avance . ' %',
                            $acta->term_ingreso,
                            $acta->term_salida,
                            $acta->num_acta,
                            $acta->credito_puente
                        ]);
                    }
                    $num = 'A1:N' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }

        )->download('xls');
    }

    public function indexDescargas(Request $request){

        $proyecto = $request->proyecto;
        $etapa = $request->etapa;
        $manzana = $request->manzana;
        $lote = $request->lote;
        $fecha1 = $request->fecha1;
        $fecha2 = $request->fecha2;
        $busqueda = $request->busqueda;

        $query = Licencia::join('lotes','licencias.id','=','lotes.id')
            ->join('modelos','lotes.modelo_id','=','modelos.id')
            ->join('etapas','lotes.etapa_id','=','etapas.id')
            ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
            ->select('licencias.id','licencias.fecha_licencia as fecha','licencias.fecha_acta','licencias.fecha_predial',
                'licencias.foto_lic as archivo','licencias.foto_acta','licencias.foto_predial','lotes.manzana','lotes.num_lote',
                'etapas.num_etapa','fraccionamientos.nombre as proyecto', 'modelos.nombre as modelo', 'lotes.calle', 'lotes.numero',
                'lotes.precio_base','lotes.obra_extra','lotes.ajuste','lotes.sobreprecio','lotes.excedente_terreno');

        switch($busqueda){
            case 'licencias.fecha_licencia':{
                if($fecha1 != '' && $fecha2 != ''){
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_lic', '!=', NULL)
                            ->whereBetween($busqueda, [$fecha1, $fecha2])
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }
                else{
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_lic', '!=', NULL)
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_lic', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }  
                break;
            }
            case 'licencias.fecha_acta':{
                if($fecha1 != '' && $fecha2 != ''){
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_acta', '!=', NULL)
                            ->whereBetween($busqueda, [$fecha1, $fecha2])
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }
                else{
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_acta', '!=', NULL)
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_acta', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }  
                break;
            }
            case 'licencias.fecha_predial':{
                if($fecha1 != '' && $fecha2 != ''){
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_predial', '!=', NULL)
                            ->whereBetween($busqueda, [$fecha1, $fecha2])
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->whereBetween($busqueda, [$fecha1, $fecha2])
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }
                else{
                    if($proyecto == ''){
                        $lotes = $query
                            ->where('licencias.foto_predial', '!=', NULL)
                            ->orderBy('fraccionamientos.nombre','asc')
                            ->orderBy('etapas.num_etapa','asc')
                            ->orderBy('lotes.manzana','asc')
                            ->orderBy('lotes.num_lote','asc')
                            ->paginate(10);
                    }
                    else{
                        if($etapa == '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana == '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote == ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                        elseif($etapa != '' && $manzana != '' && $lote != ''){
                            $lotes = $query
                                ->where('licencias.foto_predial', '!=', NULL)
                                ->where('lotes.fraccionamiento_id', '=', $proyecto)
                                ->where('lotes.etapa_id', '=', $etapa)
                                ->where('lotes.manzana', '=', $manzana)
                                ->where('lotes.num_lote', '=', $lote)
                                ->orderBy('fraccionamientos.nombre','asc')
                                ->orderBy('etapas.num_etapa','asc')
                                ->orderBy('lotes.manzana','asc')
                                ->orderBy('lotes.num_lote','asc')
                                ->paginate(10);
                        }
                    }
                }  
                break;
            }
        }
        

        
        return [
            'pagination' => [
                'total'         => $lotes->total(),
                'current_page'  => $lotes->currentPage(),
                'per_page'      => $lotes->perPage(),
                'last_page'     => $lotes->lastPage(),
                'from'          => $lotes->firstItem(),
                'to'            => $lotes->lastItem(),
            ],
            'lotes' => $lotes, 'criterio'=> $busqueda

        ];
    }
}
