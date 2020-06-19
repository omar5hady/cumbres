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
    //Contratos
    public function listarFacturaContratos(Request $request){
        $facturas = Contrato::join('creditos', 'contratos.id', '=', 'creditos.id')
            ->join('clientes', 'clientes.id', '=', 'creditos.prospecto_id')
            ->join('personal as c', 'c.id', '=', 'clientes.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->select(
                'contratos.id',//como es el mismo que el de credito seusara este id para las consultas
                DB::raw('CONCAT(c.nombre, " ", c.apellidos) as nombre'),
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
                
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar)
                            ->when($request->b_etapa, function($query, $b){
                                return $query->where('creditos.etapa', '=', $b);
                            })
                            ->when($request->b_gen, function($query, $b){
                                return $query->where('nombre', 'like', "%$b%")
                                    ->orWhere('contratos.e_monto', 'like', "%$b%")
                                    ->orWhere('contratos.e_folio_factura', 'like', "%$b%")
                                    ->orWhere('creditos.num_lote', 'like', "%$b%");
                            });
            }else{
                $facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");
            }
        }else{
            $facturas = $facturas->whereNull('contratos.e_factura');
        }
        

        $facturas = $facturas->paginate(30);

        return $facturas;
    }

    public function cargarFacturaContratos(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $contrato = Contrato::findOrFail($request->id);

        if($contrato->e_factura != ""){
            try{
                File::delete(public_path().'/files/facturas/contratos/'.$contrato->e_factura);
            }catch (Exception $e){
                return $e->getMessage();
            }
        }

        try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/contratos/'), $name);
            
            if($moved){
                $contrato->e_factura = $name;
                $contrato->e_folio_factura = $request->upFolio;
                $contrato->e_monto = $request->upMonto;
                $contrato->e_f_carga_factura = Carbon::now()->format('Y-m-d');
                $contrato->save();
            }

        }catch (Exception $e){
            DB::rollBack();
        }
    }

    public function descargaFacturaC($name){
        $pathtoFile = public_path().'/files/facturas/contratos/'.$name;
        return response()->download($pathtoFile);
    }

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

        if($request->buscar != '' || $request->b_gen != ''){
            if($request->criterio == "lotes.fraccionamiento_id"){
                
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar)
                            ->when($request->b_etapa, function($query, $b){
                                return $query->where('creditos.etapa', '=', $b);
                            })
                            ->when($request->b_gen, function($query, $b){
                                return $query->where('nombre', 'like', "%$b%")
                                    ->orWhere('depositos.monto', 'like', "%$b%")
                                    ->orWhere('depositos.folio_factura', 'like', "%$b%")
                                    ->orWhere('depositos.cant_depo', 'like', "%$b%")
                                    ->orWhere('depositos.concepto', 'like', "%$b%")
                                    ->orWhere('creditos.num_lote', 'like', "%$b%");
                            });
            }else{
                $facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");
            }
        }else{
            $facturas = $facturas->whereNull('depositos.factura');
        }
        

        $facturas = $facturas->distinct('depositos.id')->paginate(30);

        return $facturas;
    }

    public function cargarFacturaDepositos(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Deposito::findOrFail($request->id);

        if($deposito->factura != ""){
            try{
                File::delete(public_path().'/files/facturas/depositos/'.$deposito->factura);
            }catch (Exception $e){
                return $e->getMessage();
            }
        }

        try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depositos/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        }catch (Exception $e){
            DB::rollBack();
        }
    }

    public function descargaFacturaD($name){
        $pathtoFile = public_path().'/files/facturas/depositos/'.$name;
        return response()->download($pathtoFile);
    }

    //liquidacion de credito
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
                
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar)
                            ->when($request->b_etapa, function($query, $b){
                                return $query->where('creditos.etapa', '=', $b);
                            })
                            ->when($request->b_gen, function($query, $b){
                                return $query->where('nombre', 'like', "%$b%")
                                    ->orWhere('creditos.monto', 'like', "%$b%")
                                    ->orWhere('creditos.folio_factura', 'like', "%$b%")
                                    ->orWhere('creditos.num_lote', 'like', "%$b%");
                            });
            }else{
                $facturas = $facturas->where("$request->criterio", 'like', "%$request->b_gen%");
            }
        }else{
            $facturas = $facturas->whereNull('creditos.factura');
        }
        
        //para que aparezca debe tener fecha de firma de escrituras != null en expediente
        $facturas = $facturas->whereNotNull('expedientes.fecha_firma_esc')
        ->distinct('contratos.id')->paginate(30);

        return $facturas;
    }

    public function cargarFacturaLiqCredito(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Credito::findOrFail($request->id);

        if($deposito->factura != ""){
            try{
                File::delete(public_path().'/files/facturas/lcredito/'.$deposito->factura);
            }catch (Exception $e){
                return $e->getMessage();
            }
        }

        try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/lcredito/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        }catch (Exception $e){
            DB::rollBack();
        }
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
                
                $facturas = $facturas->where('lotes.fraccionamiento_id', '=', $request->buscar)
                            ->when($request->b_etapa, function($query, $b){
                                return $query->where('creditos.etapa', '=', $b);
                            })
                            ->when($request->b_gen, function($query, $b){
                                return $query->where('nombre', 'like', "%$b%")
                                    ->orWhere('dep_creditos.monto', 'like', "%$b%")
                                    ->orWhere('dep_creditos.folio_factura', 'like', "%$b%")
                                    ->orWhere('creditos.num_lote', 'like', "%$b%")
                                    ->orWhere('dep_creditos.banco', 'like', "%$b%")
                                    ->orWhere('dep_creditos.concepto', 'like', "%$b%")
                                    ->orWhere('dep_creditos.cant_depo', 'like', "%$b%");
                            });
            }else{
                $facturas = $facturas->where($request->criterio, 'like', "%$request->b_gen%");
            }
        }else{
            $facturas = $facturas->whereNull('dep_creditos.factura');
        }
        

        $facturas = $facturas->distinct('dep_creditos.id')->paginate(30);

        return $facturas;
    }

    public function cargarFacturaDepCredito(Request $request){

        setLocale(LC_TIME, 'es_MX.utf8');

        $deposito = Dep_credito::findOrFail($request->id);

        if($deposito->factura != ""){
            try{
                File::delete(public_path().'/files/facturas/depocredito/'.$deposito->factura);
            }catch (Exception $e){
                return $e->getMessage();
            }
        }

        try{

            $name = uniqId().'.'.$request->upfil->getClientOriginalExtension();
            $moved = $request->upfil->move(public_path('/files/facturas/depocredito/'), $name);
            
            if($moved){
                $deposito->factura = $name;
                $deposito->folio_factura = $request->upFolio;
                $deposito->monto = $request->upMonto;
                $deposito->f_carga_factura = Carbon::now()->format('Y-m-d');
                $deposito->save();
            }

        }catch (Exception $e){
            DB::rollBack();
        }
    }

    public function descargaFacturaDC($name){
        $pathtoFile = public_path().'/files/facturas/depocredito/'.$name;
        return response()->download($pathtoFile);
    }
}
