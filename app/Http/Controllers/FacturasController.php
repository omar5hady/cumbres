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
                'creditos.valor_terreno',
                'creditos.porcentaje_terreno',

                'depositos.pago_id',

                'depositos.cant_depo',
                'depositos.banco',
                'depositos.concepto',

                'depositos.factura',
                'depositos.folio_factura',
                'depositos.monto',
                'depositos.f_carga_factura',

                'depositos.factura_terreno',
                'depositos.folio_factura_terreno',
                'depositos.monto_terreno',
                'depositos.f_carga_factura_terreno',

                'lotes.emp_constructora',
                'lotes.emp_terreno',

                'pagos_contratos.restante',
                'pagos_contratos.monto_pago'
        );

        // if($request->historial == 1){
        //     $facturas = $facturas->where('depositos.factura','!=',NULL)
        //                         ->where('depositos.factura','!=','');
        // }

        if($request->b_empresa != ''){
            $facturas= $facturas->where('lotes.emp_constructora','=',$request->b_empresa);
        }

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
                            ->where('depositos.banco', '!=', '0102030405-Scotiabank')
                            ->distinct('depositos.id')
        ->paginate(15);

        foreach($facturas as $index => $f){

            $totalDep = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                ->join('dep_creditos', 'inst_seleccionadas.id', '=', 'dep_creditos.inst_sel_id')
                ->select(
                    DB::raw('SUM(cant_depo) as pendiente'),
                    DB::raw('SUM(monto_terreno) as pagado')
                )
                ->where('contratos.id',$f->cId)
                ->groupBy('inst_sel_id')
            ->get();
            
            $saldoTotal = Deposito::select(
                    DB::raw('SUM(cant_depo) as pendiente'),
                    DB::raw('SUM(monto_terreno) as pagado')
                )
                ->where('pago_id',$f->pago_id)
                ->groupBy('pago_id')
            ->get();

            if(sizeof($totalDep)){
                $totalDep = $saldoTotal[0]->pagado + $totalDep[0]->pagado;
            }else $totalDep = $saldoTotal[0]->pagado;

            if($f->monto_terreno != 0){
                $pendiente = ($f->valor_terreno - $totalDep)+$f->monto_terreno;
            }else $pendiente = $f->valor_terreno - $totalDep;

            $f->terreno_pagado = $totalDep;
            $f->pendiente_terre = $pendiente;
            $f->porc_deposito = $f->cant_depo*($f->porcentaje_terreno/100);
        }

        return $facturas;
    }

    public function cargarFacturaDepositos(Request $request){

        //return $request;

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Deposito::findOrFail($request->id);

        if($request->upFolio != ""){
            if($deposito->factura != ""){
                File::delete(public_path().'/files/facturas/depositos/'.$deposito->factura);
            }

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depositos/'), $name);

            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }
        }

        if($request->upFolioTer != ""){

            if($deposito->factura_terreno != ""){
                File::delete(public_path().'/files/facturas/terreno/'.$deposito->factura_terreno);
            }

            $name = uniqId().'.'.$request->upfilTer->getClientOriginalExtension();
            $moved = $request->upfilTer->move(public_path('/files/facturas/terreno/'), $name);

            if($moved){
                $deposito->factura_terreno = $name;
                $deposito->folio_factura_terreno = $request->upFolioTer;
                $deposito->monto_terreno = $request->upMontoTer;
                $deposito->f_carga_factura_terreno = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }
        }
    }

    public function descargaFacturaD($name){
        $pathtoFile = public_path().'/files/facturas/depositos/'.$name;
        return response()->download($pathtoFile);
    }

    public function descargaFacturaTer($name){
        $pathtoFile = public_path().'/files/facturas/terreno/'.$name;
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
                'contratos.e_f_carga_factura',
                'contratos.e_factura_concretania',
                'contratos.e_folio_factura_concretania',
                'contratos.e_monto_concretania',
                'contratos.e_f_carga_factura_concretania',
                'lotes.emp_constructora'
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
                $facturas = $facturas->where('contratos.status', '=', 3)->where('contratos.e_factura', '=', NULL);
            else{
                $facturas = $facturas->where('contratos.status', '=', 3)->where('contratos.e_factura','!=',NULL)
                                            ->where('contratos.e_factura','!=','');
            }
        }
        
        if($request->b_empresa != ''){
            $facturas= $facturas->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $facturas = $facturas->where('inst_seleccionadas.elegido', '=', 1)
            ->where('inst_seleccionadas.tipo_credito', '=', "Crédito Directo")
        ->paginate(15);

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

    public function cargarFacturaContratosConcretania(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $contrato = Contrato::findOrFail($request->id);

        if($contrato->e_factura_concretania != ""){
            //try{
                File::delete(public_path().'/files/facturas/contratos/concretania/'.$contrato->e_factura_concretania);
            //}catch (Exception $e){
            //    return $e->getMessage();
            //}
        }

        //try{

            $name = uniqId().'.'.$request->upfilCon->getClientOriginalExtension();
            $moved = $request->upfilCon->move(public_path('/files/facturas/contratos/concretania/'), $name);
            
            if($moved){
                $contrato->e_factura_concretania = $name;
                $contrato->e_folio_factura_concretania = $request->upFolioCon;
                $contrato->e_monto_concretania = $request->upMontoCon;
                $contrato->e_f_carga_factura_concretania = Carbon::now()->format('Y-m-d');
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

    public function descargaFacturaCon($name){
        $pathtoFile = public_path().'/files/facturas/contratos/concretania/'.$name;
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

        if($request->b_empresa != ''){
            $facturas= $facturas->where('lotes.emp_constructora','=',$request->b_empresa);
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
                'inst_seleccionadas.id as insId',
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
                //DB::raw("DATEDIFF('".Carbon::now()->format('Y-m-d')."', dep_creditos.fecha_deposito) as dias"),
                'dep_creditos.fecha_deposito',
                'c.rfc',
                'creditos.num_lote',
                'creditos.fraccionamiento',
                'creditos.etapa',
                'creditos.manzana',
                'creditos.valor_terreno',
                'creditos.porcentaje_terreno',

                'dep_creditos.banco',
                'dep_creditos.concepto',
                'dep_creditos.cant_depo',

                'dep_creditos.factura',
                'dep_creditos.folio_factura',
                'dep_creditos.monto',
                'dep_creditos.f_carga_factura',

                'dep_creditos.factura_terreno',
                'dep_creditos.folio_factura_terreno',
                'dep_creditos.monto_terreno',
                'dep_creditos.f_carga_factura_terreno',
                'lotes.emp_constructora'
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

        if($request->b_empresa != ''){
            $facturas= $facturas->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        $facturas = $facturas->distinct('dep_creditos.id')
        ->paginate(15);
        
        foreach($facturas as $index => $f){
            $totalDep =0;
            $totalDepCont = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
                ->join('pagos_contratos', 'contratos.id', '=', 'pagos_contratos.contrato_id')
                ->join('depositos', 'pagos_contratos.id', '=', 'depositos.pago_id')
                ->select(
                    DB::raw('SUM(monto_terreno) as pagado')
                )
                ->where('contratos.id',$f->cId)
            ->get();
            
            $saldoTotal = Dep_credito::select(
                    DB::raw('SUM(monto_terreno) as pagado')
                )
                ->where('inst_sel_id',$f->insId)
            ->get();

            //if(sizeof($totalDepCont)){
                $totalDep = $saldoTotal[0]->pagado + $totalDepCont[0]->pagado;
            //}else $totalDep = $saldoTotal[0]->pagado;

            if($f->monto_terreno!=0){
                $pendiente = ($f->valor_terreno - $totalDep)+$f->monto_terreno;
            }else $pendiente = $f->valor_terreno - $totalDep;

            $f->terreno_pagado = $totalDep;
            $f->pendiente_terre = $pendiente;
            $f->porc_deposito = $f->cant_depo*($f->porcentaje_terreno/100);
        }
        return $facturas;
    }

    public function cargarFacturaDepCredito(Request $request){
        
        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Dep_credito::findOrFail($request->id);

        if($request->upFolio != ""){

            if($deposito->factura != ""){
                File::delete(public_path().'/files/facturas/depocredito/'.$deposito->factura);
            }

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depocredito/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }
        }

        if($request->upFolioTer != ""){

            if($deposito->factura != ""){
                File::delete(public_path().'/files/facturas/depocreditoterreno/'.$deposito->factura_terreno);
            }

            $name = uniqId().'.'.$request->upfilTer->getClientOriginalExtension();
            $moved = $request->upfilTer->move(public_path('/files/facturas/depocreditoterreno/'), $name);
            
            if($moved){
                $deposito->factura_terreno = $name;
                $deposito->folio_factura_terreno = $request->upFolioTer;
                $deposito->monto_terreno = $request->upMontoTer;
                $deposito->f_carga_factura_terreno = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }
        }
    }

    public function descargaFacturaDC($name){
        $pathtoFile = public_path().'/files/facturas/depocredito/'.$name;
        return response()->download($pathtoFile);
    }

    public function descargaFacturaDCT($name){
        $pathtoFile = public_path().'/files/facturas/depocreditoterreno/'.$name;
        return response()->download($pathtoFile);
    }
}
