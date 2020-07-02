<?php

namespace App\Http\Controllers;

use App\Contrato;
use App\Deposito;
use App\Credito;
use App\Dep_credito;
use DB;
use File;
use Carbon\Carbon;

use Illuminate\Http\Request;

class FacturasController extends Controller
{
    //Depositos pagares
    public function listarFacturaDepositos(Request $request){
        
        $facturas = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('clientes', 'clientes.id', '=', 'creditos.prospecto_id')
            ->join('personal as c', 'c.id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('pagos_contratos', 'contratos.id', '=', 'pagos_contratos.contrato_id')
            ->join('depositos', 'pagos_contratos.id', '=', 'depositos.pago_id')
            ->select(
                'depositos.id',
                'contratos.id as cId',
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
                //DB::raw("DATEDIFF('".Carbon::now()->format('Y-m-d')."', depositos.fecha_pago) as dias"),
                'depositos.fecha_pago',
                'c.rfc',
                'creditos.num_lote',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.manzana',

                'depositos.cant_depo',
                'depositos.banco',
                'depositos.concepto',

                'depositos.factura',
                'depositos.folio_factura',
                'depositos.monto',
                'depositos.f_carga_factura'
        );

        // if($request->historial == 1){
        //     $facturas = $facturas->where('depositos.factura','!=',NULL)
        //                         ->where('depositos.factura','!=','');
        // }

        if($request->buscar != '' || $request->b_gen != ''){
            if($request->criterio == "lotes.fraccionamiento_id"){

                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar);

                if($request->b_etapa != "") $facturas = $facturas->where('creditos.etapa', '=', $request->b_etapa);

                if($request->b_gen != ""){
                    $facturas = $facturas->where('creditos.num_lote', '=', $request->b_gen);
                }
                
            }else{
                if($request->criterio == 'nombre'){
                    $facturas = $facturas->where(DB::raw('CONCAT(nombre," ",apellidos)'), 'like', "%$request->b_gen%");
                }else{$facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");}
            }
            if($request->historial == 1){
                $facturas = $facturas->where('depositos.factura','!=',NULL)
                                ->where('depositos.factura','!=','');
            }
        }else{
            if($request->historial == 0)
                $facturas = $facturas->whereNull('depositos.factura');
            else{
                $facturas = $facturas->where('depositos.factura','!=',NULL)
                                ->where('depositos.factura','!=','');
            }
        }

        
        

        $facturas = $facturas->where('pagos_contratos.tipo_pagare', '!=', 1)
                            ->where('depositos.banco', '!=', '0102030405-Scotiabank')->distinct('depositos.id')->paginate(15);

        return $facturas;
    }

    public function cargarFacturaDepositos(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Deposito::findOrFail($request->id);

        if($deposito->factura != ""){
            //try{
                File::delete(public_path().'/files/facturas/depositos/'.$deposito->factura);
            //}catch (Exception $e){
            //    return $e->getMessage();
            //}
        }

        //try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depositos/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        //}catch (Exception $e){
        //    DB::rollBack();
        //}
    }

    public function descargaFacturaD($name){
        $pathtoFile = public_path().'/files/facturas/depositos/'.$name;
        return response()->download($pathtoFile);
    }
    
    //Creditos Directos
    public function listarFacturaContratos(Request $request){
        $facturas = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('clientes', 'clientes.id', '=', 'creditos.prospecto_id')
            ->join('personal as c', 'c.id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->select(
                'contratos.id',//como es el mismo que el de credito seusara este id para las consultas
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
                //DB::raw("DATEDIFF('".Carbon::now()->format('Y-m-d')."', contratos.fecha_status) as dias"),
                'contratos.fecha_status',
                'contratos.status',
                'c.rfc',
                'creditos.num_lote',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.manzana',
                'contratos.e_factura',
                'contratos.e_folio_factura',
                'contratos.e_monto',
                'contratos.e_f_carga_factura'
        );

        if($request->buscar != '' || $request->b_gen != ''){
            if($request->criterio == "lotes.fraccionamiento_id"){
                
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar);

                if($request->b_etapa != "") $facturas = $facturas->where('creditos.etapa', '=', $request->b_etapa);

                if($request->b_gen != ""){
                    $facturas = $facturas->where('creditos.num_lote', '=', $request->b_gen);
                }
            }else{
                if($request->criterio == 'nombre'){
                    $facturas = $facturas->where(DB::raw('CONCAT(nombre," ",apellidos)'), 'like', "%$request->b_gen%");
                }else{$facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");}
            }
            if($request->historial == 1){
                $facturas = $facturas->where('contratos.e_factura','!=',NULL)
                                            ->where('contratos.e_factura','!=','');
            }
        }else{
            if($request->historial == 0)
                $facturas = $facturas->where('contratos.status', '=', 3)->whereNull('contratos.e_factura');
            else{
                $facturas = $facturas->where('contratos.status', '=', 3)->where('contratos.e_factura','!=',NULL)
                                            ->where('contratos.e_factura','!=','');
            }
        }
        

        $facturas = $facturas->where('inst_seleccionadas.elegido', '=', 1)->where('inst_seleccionadas.tipo_credito', '=', "Crédito Directo")->paginate(15);

        return $facturas;
    }

    public function cargarFacturaContratos(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $contrato = Contrato::findOrFail($request->id);

        if($contrato->e_factura != ""){
            //try{
                File::delete(public_path().'/files/facturas/contratos/'.$contrato->e_factura);
            //}catch (Exception $e){
            //    return $e->getMessage();
            //}
        }

        //try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/contratos/'), $name);
            
            if($moved){
                $contrato->e_factura = $name;
                $contrato->e_folio_factura = $request->upFolio;
                $contrato->e_monto = $request->upMonto;
                $contrato->e_f_carga_factura = Carbon::now()->format('Y-m-d');
                $contrato->save();
            }

        //}catch (Exception $e){
        //    DB::rollBack();
        //}
    }

    public function descargaFacturaC($name){
        $pathtoFile = public_path().'/files/facturas/contratos/'.$name;
        return response()->download($pathtoFile);
    }


    //Creditos Escriturados
    public function listarFacturaLiqCredito(Request $request){
        $facturas = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('clientes', 'clientes.id', '=', 'creditos.prospecto_id')
            ->join('personal as c', 'c.id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('expedientes', 'contratos.id', '=', 'expedientes.id')
            ->select(
                'creditos.id',
                'contratos.id as cId',
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
                'expedientes.fecha_firma_esc',
                'c.rfc',
                'creditos.num_lote',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.factura',
                'creditos.folio_factura',
                'creditos.monto',
                'creditos.f_carga_factura'
        );

        if($request->buscar != '' || $request->b_gen != ''){
            if($request->criterio == "lotes.fraccionamiento_id"){
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar);

                if($request->b_etapa != "") $facturas = $facturas->where('creditos.etapa', '=', $request->b_etapa);

                if($request->b_gen != ""){
                    $facturas = $facturas->where('creditos.num_lote', '=', $request->b_gen);
                }
                
            }else{
                if($request->criterio == 'nombre'){
                    $facturas = $facturas->where(DB::raw('CONCAT(nombre," ",apellidos)'), 'like', "%$request->b_gen%");
                }else{$facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");}
            }
            if($request->historial == 1){
                $facturas = $facturas->where('creditos.factura','!=',NULL)->where('creditos.factura','!=','');
            }
        }else{
            if($request->historial == 0)
                $facturas = $facturas->whereNull('creditos.factura');
            else{
                $facturas = $facturas->where('creditos.factura','!=',NULL)->where('creditos.factura','!=','');
            }
        }
        
        //para que aparezca debe tener fecha de firma de escrituras != null en expediente
        $facturas = $facturas->where('inst_seleccionadas.elegido', '=', 1)
            ->where('inst_seleccionadas.tipo_credito', '!=', "Crédito Directo")
            ->whereNotNull('expedientes.fecha_firma_esc')
        ->distinct('creditos.id')->paginate(15);

        return $facturas;
    }

    public function cargarFacturaLiqCredito(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Credito::findOrFail($request->id);

        if($deposito->factura != ""){
            //try{
                File::delete(public_path().'/files/facturas/lcredito/'.$deposito->factura);
            //}catch (Exception $e){
            //    return $e->getMessage();
            //}
        }

        //try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/lcredito/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        //}catch (Exception $e){
        //    DB::rollBack();
        //}
    }

    public function descargaFacturaLC($name){
        $pathtoFile = public_path().'/files/facturas/lcredito/'.$name;
        return response()->download($pathtoFile);
    }

    //depositos de credito
    public function listarFacturaDepCredito(Request $request){
        $facturas = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('clientes', 'clientes.id', '=', 'creditos.prospecto_id')
            ->join('personal as c', 'c.id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
            ->join('dep_creditos', 'inst_seleccionadas.id', '=', 'dep_creditos.inst_sel_id')
            ->select(
                'dep_creditos.id',
                'contratos.id as cId',
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
                //DB::raw("DATEDIFF('".Carbon::now()->format('Y-m-d')."', dep_creditos.fecha_deposito) as dias"),
                'dep_creditos.fecha_deposito',
                'c.rfc',
                'creditos.num_lote',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.manzana',

                'dep_creditos.banco',
                'dep_creditos.concepto',
                'dep_creditos.cant_depo',

                'dep_creditos.factura',
                'dep_creditos.folio_factura',
                'dep_creditos.monto',
                'dep_creditos.f_carga_factura'
        );

        if($request->buscar != '' || $request->b_gen != ''){
            if($request->criterio == "lotes.fraccionamiento_id"){
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar);

                if($request->b_etapa != "") $facturas = $facturas->where('creditos.etapa', '=', $request->b_etapa);

                if($request->b_gen != ""){
                    $facturas = $facturas->where('creditos.num_lote', '=', $request->b_gen);
                }

            }else{
                if($request->criterio == 'nombre'){
                    $facturas = $facturas->where(DB::raw('CONCAT(nombre," ",apellidos)'), 'like', "%$request->b_gen%");
                }else{$facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");}
            }
            if($request->historial == 1){
                $facturas = $facturas->where('dep_creditos.factura','!=',NULL)->where('dep_creditos.factura','!=','');
            }
        }else{
            if($request->historial == 0)
                $facturas = $facturas->whereNull('dep_creditos.factura');
            else{
                $facturas = $facturas->where('dep_creditos.factura','!=',NULL)->where('dep_creditos.factura','!=','');
            }
        }
        

        $facturas = $facturas->distinct('dep_creditos.id')->paginate(15);

        return $facturas;
    }

    public function cargarFacturaDepCredito(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Dep_credito::findOrFail($request->id);

        if($deposito->factura != ""){
            //try{
                File::delete(public_path().'/files/facturas/depocredito/'.$deposito->factura);
            //}catch (Exception $e){
            //    return $e->getMessage();
            //}
        }

        //try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depocredito/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        //}catch (Exception $e){
        //    DB::rollBack();
        //}
    }

    public function descargaFacturaDC($name){
        $pathtoFile = public_path().'/files/facturas/depocredito/'.$name;
        return response()->download($pathtoFile);
    }
}
