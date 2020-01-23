<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DB;
use Auth;
use App\Vendedor;
use App\Expediente;
use Excel;
use Carbon\Carbon;
use App\Pago_contrato;
use NumerosEnLetras;
use App\Lote;
use App\Credito;
use App\Det_comision;

class ComisionesController extends Controller
{
    public function indexContratos(Request $request){

        $buscar = $request->buscar;
        $vendedor = $request->vendedor;
        $etapa = $request->etapa;
        $manzana = $request->manzana;
        $lote = $request->lote;
        $criterio = $request->criterio;

        if($vendedor == ''){
            $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_comp"),'personal.id','vendedores.tipo')->get();

            if($buscar == ''){
                $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id',
                        'contratos.fecha',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'contratos.avance_lote',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.vendedor_id',
                        'creditos.precio_venta')
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                
            }
        }
        else{
            $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_comp"),'personal.id','vendedores.tipo')
                ->where('vendedores.id','=',$vendedor)->get();

            if($buscar == ''){
                $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id',
                        'contratos.fecha',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'contratos.avance_lote',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.vendedor_id',
                        'creditos.precio_venta')
                ->where('creditos.vendedor_id','=',$vendedor)
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','=','lotes.id')
                    ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->select('contratos.id',
                            'contratos.fecha',
                            DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                            'contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa',
                            'creditos.manzana',
                            'creditos.num_lote',
                            'creditos.vendedor_id',
                            'creditos.precio_venta')
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                
            }
        }

        

        if(sizeof($contratos)){
            foreach($contratos as $et=>$contrato){
                $contrato->nom_vendedor='';
                foreach($vendedores as $index=>$vendedor){
                    if($contrato->vendedor_id == $vendedor->id)
                    $contrato->nom_vendedor=$vendedor->nombre_comp;
                    $contrato->tipoVendedor=$vendedor->tipo;
                }
            }
        }

        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        return [ 
            'pagination' => [
            'total'         => $contratos->total(),
            'current_page'  => $contratos->currentPage(),
            'per_page'      => $contratos->perPage(),
            'last_page'     => $contratos->lastPage(),
            'from'          => $contratos->firstItem(),
            'to'            => $contratos->lastItem(),
            ],'contratos'=>$contratos,'hoy'=>$hoy];
    }

    public function agregarExpediente(Request $request){
        $contrato = Contrato::findOrFail($request->id);
        $contrato->comision = 1;
        $contrato->porcentaje_exp = $request->porcentaje;
        $contrato->fecha_exp = $request->fecha;
        $contrato->save();
    }

    public function ventasAsesor(Request $request){
        $fechaIni = $request->anio.'-'.$request->mes.'-01';
        $fechaFin = $request->anio.'-'.$request->mes.'-15';

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha')
                    ->where('contratos.status','=',3)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$request->mes)
                    ->whereYear('contratos.fecha',$request->anio)
                    ->orderBy('contratos.id','asc')
                    ->get();

        $ventasQuincena = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp','contratos.fecha_exp')
                    ->where('contratos.status','=',3)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereBetween('contratos.fecha', [$fechaIni, $fechaFin])
                    ->count();

        $numVentas = $ventas->count();

        if($request->mes == '01'){
            $mesAnt = 12;
            $anioAnt = $request->anio - 1;
        }
        else{
            $mesAnt = $request->mes - 1;
            $anioAnt = $request->anio;
        }

        $ventaPasada = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                    'creditos.fraccionamiento as proyecto',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp','contratos.fecha_exp')
            ->where('contratos.status','=',3)
            ->where('vendedor_id','=',$request->vendedor)
            ->whereMonth('contratos.fecha',$mesAnt)
            ->whereYear('contratos.fecha',$anioAnt)
            ->count();

        $vendedor = Vendedor::findOrFail($request->vendedor);
        $tipo = $vendedor->tipo;
        $esquema = $vendedor->esquema;

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','=',0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$request->mes)
                    ->whereYear('contratos.fecha',$request->anio)
                    ->count();

        return['ventas'=>$ventas, 
                'cancelaciones'=>$cancelaciones, 
                'numVentas' => $numVentas,
                'pasada' => $ventaPasada,
                'quincena' => $ventasQuincena,
                'tipo'=>$tipo,
                'esquema'=>$esquema
            ];
    }

    
}
