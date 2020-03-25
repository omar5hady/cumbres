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
use App\Comision;

class ComisionesController extends Controller
{
    public function indexContratos(Request $request){

        $buscar = $request->buscar;
        $vendedor = $request->vendedor;
        $etapa = $request->etapa;
        $manzana = $request->manzana;
        $lote = $request->lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id',
                        'contratos.fecha',
                        'contratos.comision',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'contratos.avance_lote',
                        'creditos.fraccionamiento as proyecto',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.vendedor_id',
                        'creditos.precio_venta');

        if($vendedor == ''){
            $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_comp"),'personal.id','vendedores.tipo')->get();

            if($buscar == ''){
                $contratos = $query
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = $query
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
                $contratos = $query
                ->where('creditos.vendedor_id','=',$vendedor)
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = $query
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

    public function historialExpedientes(Request $request){

        $buscar = $request->buscar;
        $vendedor = $request->vendedor;
        $etapa = $request->etapa;
        $manzana = $request->manzana;
        $lote = $request->lote;
        $criterio = $request->criterio;

        $query = Contrato::join('creditos','contratos.id','=','creditos.id')
                ->join('lotes','creditos.lote_id','=','lotes.id')
                ->join('clientes', 'creditos.prospecto_id', '=', 'clientes.id')
                ->join('personal as c', 'clientes.id', '=', 'c.id')
                ->select('contratos.id',
                        'contratos.fecha',
                        'contratos.comision',
                        DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                        'contratos.avance_lote',
                        'creditos.fraccionamiento as proyecto',
                        'contratos.fecha_exp','contratos.porcentaje_exp',
                        'creditos.etapa',
                        'creditos.manzana',
                        'creditos.num_lote',
                        'creditos.vendedor_id',
                        'creditos.precio_venta');

        if($vendedor == ''){
            $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_comp"),'personal.id','vendedores.tipo')->get();

            if($buscar == ''){
                $contratos = $query
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = $query
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                
            }
        }
        else{
            $vendedores = Vendedor::join('personal','vendedores.id','=','personal.id')
                ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_comp"),'personal.id','vendedores.tipo')
                ->where('vendedores.id','=',$vendedor)->get();

            if($buscar == ''){
                $contratos = $query
                ->where('creditos.vendedor_id','=',$vendedor)
                ->where('contratos.status','=',3)
                ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
            }
            else{
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                if($buscar!='' && $etapa == '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->whree('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana == '' && $lote != ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote == ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
                }
                elseif($buscar!='' && $etapa != '' && $manzana != '' && $lote != ''){
                    $contratos = $query
                    ->where('creditos.vendedor_id','=',$vendedor)
                    ->where('contratos.status','=',3)
                    ->where('lotes.fraccionamiento_id','=',$buscar)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('lotes.manzana','=',$manzana)
                    ->where('lotes.num_lote','=',$lote)
                    ->where('contratos.fecha_exp','!=',NULL)->paginate(8);
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

        return [ 
            'pagination' => [
            'total'         => $contratos->total(),
            'current_page'  => $contratos->currentPage(),
            'per_page'      => $contratos->perPage(),
            'last_page'     => $contratos->lastPage(),
            'from'          => $contratos->firstItem(),
            'to'            => $contratos->lastItem(),
            ],'contratos'=>$contratos];
    }

    public function agregarExpediente(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $contrato = Contrato::findOrFail($request->id);
        $contrato->porcentaje_exp = $request->porcentaje;
        $contrato->fecha_exp = $request->fecha;
        $contrato->save();
    }

    public function ventasAsesor(Request $request){
        $fechaIni = $request->anio.'-'.$request->mes.'-01';
        $fechaFin = $request->anio.'-'.$request->mes.'-15';

        $ventas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->join('pagos_contratos','contratos.id','=','pagos_contratos.contrato_id')
                    ->join('depositos','pagos_contratos.id','=','depositos.pago_id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha','lotes.extra','lotes.extra_ext')
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('depositos.fecha_pago',$request->mes)
                    ->whereYear('depositos.fecha_pago',$request->anio)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','=',2)
                    ->groupBy('contratos.id')
                    ->orderBy('contratos.id','asc')
                    ->get();

        $ventasMes = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('lotes','creditos.lote_id','lotes.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote','contratos.porcentaje_exp',
                            'contratos.fecha_exp','contratos.fecha','lotes.extra','lotes.extra_ext')
                    ->where('contratos.status','=',3)
                    ->where('contratos.comision','=',0)
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

        $numVentas = $ventasMes->count();

        if($request->mes == '01'){
            $mesAnt = 12;
            $anioAnt = $request->anio - 1;
        }
        else{
            $mesAnt = $request->mes - 1;
            $anioAnt = $request->anio;
        }

        $restante = Comision::where('asesor_id','=',$request->vendedor)->where('mes','=',$mesAnt)->where('anio','=',$anioAnt)->get();
        $acum = 0;
        if(sizeof($restante)){
            $acum = $restante[0]->acumulado;
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

        $sueldo = !Carbon::parse($vendedor->fecha_sueldo)->gt(Carbon::now());

        $anticiposCancelados = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->join('det_comisiones','contratos.id','=','det_comisiones.id')
            ->join('comisiones','det_comisiones.idComision','comisiones.id')
            ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
            ->join('personal','vendedores.id','=','personal.id')
            ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                    'creditos.manzana','creditos.num_lote','det_comisiones.bono','det_comisiones.fecha_bono',
                    'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status'
                    )
            ->where('contratos.comision','=','1')
            ->where('contratos.fecha_exp','!=',NULL)
            ->where('det_comisiones.anticipo','!=',NULL)
            ->where('contratos.status','=',0)
            ->where('vendedor_id','=',$request->vendedor)
            ->whereMonth('contratos.fecha_status',$request->mes)
            ->whereYear('contratos.fecha_status',$request->anio)
            ->orderBy('contratos.id','asc')
        ->get();

        $numAnticipos = $anticiposCancelados->count();

        $totalAnticipo = 0;
        $totalBono = 0;

        if(sizeof($anticiposCancelados)){
            foreach($anticiposCancelados as $index => $anticipo){
                $totalAnticipo += $anticipo->anticipo;
                if($anticipo->fecha_bono != NULL){
                    $totalBono += $anticipo->bono;
                }
            }
        }

        $cancelaciones = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('det_comisiones','contratos.id','det_comisiones.id')
                    ->select('contratos.id','creditos.precio_venta','contratos.avance_lote',
                            'creditos.fraccionamiento as proyecto',
                            'creditos.etapa','creditos.manzana','creditos.num_lote')
                    ->where('contratos.status','=',0)
                    ->where('contratos.comision','=',1)
                    ->where('det_comisiones.anticipo','!=', 0)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('contratos.fecha',$mesAnt)
                    ->whereYear('contratos.fecha',$anioAnt)
                    ->get();


        $individualizadas = Contrato::join('creditos','contratos.id','=','creditos.id')
                    ->join('expedientes','contratos.id','=','expedientes.id')
                    ->join('det_comisiones','contratos.id','=','det_comisiones.id')
                    ->join('comisiones','det_comisiones.idComision','comisiones.id')
                    ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
                    ->join('personal','vendedores.id','=','personal.id')
                    ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                            'creditos.manzana','creditos.num_lote',
                            'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status',
                            'expedientes.fecha_firma_esc'                            
                            )
                    ->where('contratos.comision','=','1')
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.anticipo','!=',NULL)
                    ->where('contratos.status','=',3)
                    ->where('vendedor_id','=',$request->vendedor)
                    ->whereMonth('expedientes.fecha_firma_esc',$request->mes)
                    ->whereYear('expedientes.fecha_firma_esc',$request->anio)
                    ->orderBy('contratos.id','asc')
                ->get();
        
        $totalIndividualizadas = 0;

        $numIndivi = $individualizadas->count();
        
        if(sizeof($individualizadas)){
            foreach($individualizadas as $index => $indiv){
                $totalIndividualizadas += $indiv->anticipo;
            }
        }

        return['ventas'=>$ventas, 
                'anticiposCan'=>$anticiposCancelados,
                'cancelaciones'=>$cancelaciones, 
                'individualizadas'=>$individualizadas,
                'numVentas' => $numVentas,
                'numIndivi' => $numIndivi,
                'numAnticipos' => $numAnticipos,
                'pasada' => $ventaPasada,
                'quincena' => $ventasQuincena,
                'tipo'=>$tipo,
                'totalAnticipo' => $totalAnticipo,
                'totalIndividualizadas' => $totalIndividualizadas,
                'totalBono' => $totalBono,
                'esquema'=>$esquema,
                'restante'=>$acum,
                'sueldo'=>$sueldo
            ];
    }

    public function storeComision(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        // try {
        //     DB::beginTransaction();
            $comision = new Comision();
                $comision->mes = $request->mes;
                $comision->anio = $request->anio;
                $comision->total = $request->total;
                $comision->aPagar = $request->aPagar;
                $comision->num_ventas = $request->num_ventas;
                $comision->num_individualizadas = $request->num_individualizadas;
                $comision->num_cancelaciones = $request->num_cancelaciones;
                $comision->bono = $request->bono;
                $comision->asesor_id = $request->asesor_id;
                $comision->totalAnticipo = $request->anticipo;
                if($comision->aPagar < 0)
                    $comision->acumulado = ($comision->aPagar * -1);
                    
            $comision->save();

            $id = $comision->id;

            $detalle = $request->data;//Array de detalles
            //Recorro todos los elementos

            foreach($detalle as $ep=>$det)
            {
                $det_comision = new Det_comision();
                $det_comision->id = $det['id'];

                $contrato = Contrato::findOrFail($det['id']);
                $contrato->comision = 1;
                $contrato->save();

                $det_comision->comisionReal = $det['comisionReal'];
                $det_comision->porcentaje_exp = ($det['porcentaje_exp'])/100;
                if($request->tipoVendedor == 1){
                    $det_comision->extra = $det['extra_ext'];
                    $det_comision->bono = 0;
                }
                else{
                    $det_comision->extra = $det['extra'];
                    $det_comision->bono = $det['bono'];
                }
                $det_comision->porcentaje_casa = ($det_comision->comisionReal - $det_comision->extra)/$det_comision->porcentaje_exp;
                $det_comision->total = $det['comision'];
                $det_comision->anticipo = $det['anticipo'];
                $det_comision->idComision = $id;
                $det_comision->save();

            }
        // } catch (Exception $e) {
        //     DB::rollBack();
        // }
    }

    public function indexComisiones(Request $request){
        $mes = $request->b_mes;
        $anio = $request->b_anio;
        $asesor = $request->b_asesor_id;

        setlocale(LC_TIME, 'es_MX.utf8');
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $query = Comision::join('vendedores','comisiones.asesor_id', '=','vendedores.id')
            ->join('personal','vendedores.id','=','personal.id')
            ->select('comisiones.mes','comisiones.anio','comisiones.total','comisiones.num_ventas','comisiones.num_cancelaciones',
                        'comisiones.cobrado','comisiones.bono','comisiones.aPagar','comisiones.id','comisiones.totalAnticipo',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS asesor"),
                        'vendedores.tipo'
            );

        if($asesor == ''){
            if($mes == '' && $anio == ''){
                $comisiones = $query
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            elseif($mes != '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones.mes','=',$mes)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            elseif($mes == '' && $anio != ''){
                $comisiones = $query
                        ->where('comisiones.anio','=',$anio)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            else{
                $comisiones = $query
                        ->where('comisiones.mes','=',$mes)
                        ->where('comisiones.anio','=',$anio)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }

        }
        else{
            if($mes == '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            elseif($mes != '' && $anio == ''){
                $comisiones = $query
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->where('comisiones.mes','=',$mes)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            elseif($mes == '' && $anio != ''){
                $comisiones = $query
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->where('comisiones.anio','=',$anio)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }
            else{
                $comisiones = $query
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->where('comisiones.mes','=',$mes)
                        ->where('comisiones.anio','=',$anio)
                        ->orderBy('comisiones.id','desc')
                        ->orderBy('asesor','asc')->paginate();
            }

        }
        

        return[
            'pagination' => [
                'total'         => $comisiones->total(),
                'current_page'  => $comisiones->currentPage(),
                'per_page'      => $comisiones->perPage(),
                'last_page'     => $comisiones->lastPage(),
                'from'          => $comisiones->firstItem(),
                'to'            => $comisiones->lastItem(),
            ],
            'comisiones' => $comisiones, 'month' => $month, 'year' => $year
        ];
    }

    public function detalleComision(Request $request){
        $idComision = $request->id;
        $mes = $request->mes;
        $anio = $request->anio;

        if($request->mes == '01'){
            $mesAnt = 12;
            $anioAnt = $request->anio - 1;
        }
        else{
            $mesAnt = $request->mes - 1;
            $anioAnt = $request->anio;
        }

        $detalle = Det_comision::join('contratos','det_comisiones.id','=','contratos.id')
                        ->join('creditos','contratos.id','=','creditos.id')
                        ->select('det_comisiones.id','det_comisiones.fecha_anticipo','det_comisiones.porcentaje_exp',
                                'det_comisiones.total','det_comisiones.idComision','det_comisiones.extra','det_comisiones.comisionReal',
                                'det_comisiones.anticipo','det_comisiones.bono','det_comisiones.fecha_bono',
                                'det_comisiones..anticipo','creditos.precio_venta','creditos.fraccionamiento','creditos.etapa',
                                'creditos.manzana','creditos.num_lote','creditos.modelo','creditos.vendedor_id',
                                'contratos.avance_lote')
                        ->where('det_comisiones.idComision','=',$idComision)->get();

        


        $anticiposCancelados = Contrato::join('creditos','contratos.id','=','creditos.id')
                        ->join('det_comisiones','contratos.id','=','det_comisiones.id')
                        ->join('comisiones','det_comisiones.idComision','comisiones.id')
                        ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
                        ->join('personal','vendedores.id','=','personal.id')
                        ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                                'creditos.manzana','creditos.num_lote','det_comisiones.bono','det_comisiones.fecha_bono',
                                'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status'
                                )
                        ->where('contratos.comision','=','1')
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.anticipo','!=',NULL)
                        ->where('contratos.status','=',0)
                        ->where('vendedor_id','=',$detalle[0]->vendedor_id)
                        ->whereMonth('contratos.fecha_status',$request->mes)
                        ->whereYear('contratos.fecha_status',$request->anio)
                        ->orderBy('contratos.id','asc')
                    ->get();
            
        $numAnticipos = $anticiposCancelados->count();

        $totalAnticipo = 0;
        $totalBono = 0;

        if(sizeof($anticiposCancelados)){
            foreach($anticiposCancelados as $index => $anticipo){
                $totalAnticipo += $anticipo->anticipo;
                if($anticipo->fecha_bono != NULL){
                    $totalBono += $anticipo->bono;
                }
            }
        }
               
        $individualizadas = Contrato::join('creditos','contratos.id','=','creditos.id')
                                ->join('expedientes','contratos.id','=','expedientes.id')
                                ->join('det_comisiones','contratos.id','=','det_comisiones.id')
                                ->join('comisiones','det_comisiones.idComision','comisiones.id')
                                ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
                                ->join('personal','vendedores.id','=','personal.id')
                                ->select('contratos.id as folio','creditos.fraccionamiento','creditos.etapa',
                                        'creditos.manzana','creditos.num_lote',
                                        'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha_status',
                                        'expedientes.fecha_firma_esc'                            
                                        )
                                ->where('contratos.comision','=','1')
                                ->where('contratos.fecha_exp','!=',NULL)
                                ->where('det_comisiones.anticipo','!=',NULL)
                                ->where('contratos.status','=',3)
                                ->where('vendedor_id','=',$detalle[0]->vendedor_id)
                                ->whereMonth('expedientes.fecha_firma_esc',$request->mes)
                                ->whereYear('expedientes.fecha_firma_esc',$request->anio)
                                ->orderBy('contratos.id','asc')
                            ->get();
                    
        $totalIndividualizadas = 0;

        $numIndivi = $individualizadas->count();
        
        if(sizeof($individualizadas)){
            foreach($individualizadas as $index => $indiv){
                $totalIndividualizadas += $indiv->anticipo;
            }
        }

        $restante = Comision::where('asesor_id','=',$request->vendedor)->where('mes','=',$mesAnt)->where('anio','=',$anioAnt)->get();
        $acum = 0;
        if(sizeof($restante)){
            $acum = $restante[0]->acumulado;
        }

        return['detalle'=>$detalle,
                'anticiposCan'=>$anticiposCancelados,
                'individualizadas'=>$individualizadas,
                'numIndivi' => $numIndivi,
                'numAnticipos' => $numAnticipos,
                'totalAnticipo' => $totalAnticipo,
                'totalIndividualizadas' => $totalIndividualizadas,
                'totalBono' => $totalBono,
                'restante' => $restante
            ];
    }

    public function bonosPorPagar(Request $request){

        $asesor = $request->b_asesor_id;
        $proyecto = $request->b_proyecto;
        $etapa = $request->b_etapa;
        setlocale(LC_TIME, 'es_MX.utf8');
        $hoy = Carbon::today()->toDateString();

        $query = Pago_contrato::join('contratos','pagos_contratos.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('det_comisiones','contratos.id','=','det_comisiones.id')
            ->join('comisiones','det_comisiones.idComision','comisiones.id')
            ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
            ->join('personal','vendedores.id','=','personal.id')
            ->select('contratos.id as folio','pagos_contratos.pagado','creditos.fraccionamiento','creditos.etapa',
                    'creditos.manzana','creditos.num_lote','det_comisiones.idComision','det_comisiones.total',
                    'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha',
                    'det_comisiones.id as detalleId','det_comisiones.bono','det_comisiones.fecha_bono',
                    DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS asesor")
            );

        if($asesor == ''){
            if($proyecto == ''){
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                ->where('contratos.status','=',3)->paginate(12);
            }
            elseif($proyecto != '' && $etapa == ''){
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                ->where('contratos.status','=',3)->paginate(12);
            }
            else{
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                ->where('contratos.status','=',3)->paginate(12);
            }
            
        }
        else{
            if($proyecto == ''){
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('comisiones.asesor_id','=',$asesor)
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                ->where('contratos.status','=',3)->paginate(12);
            }
            elseif($proyecto != '' && $etapa == ''){
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                    ->where('comisiones.asesor_id','=',$asesor)
                ->where('contratos.status','=',3)->paginate(12);
            }
            else{
                $pendientes = $query
                    ->where('contratos.comision','=','1')
                    ->where('lotes.fraccionamiento_id','=',$proyecto)
                    ->where('lotes.etapa_id','=',$etapa)
                    ->where('contratos.fecha_exp','!=',NULL)
                    ->where('det_comisiones.fecha_bono','=',NULL)
                    ->where('pagos_contratos.num_pago','=',0)
                    ->where('pagos_contratos.pagado','>',1)
                    ->where('pagos_contratos.tipo_pagare','=',0)
                    ->where('comisiones.asesor_id','=',$asesor)
                ->where('contratos.status','=',3)->paginate(12);
            }
        }
        
        return[
            'pagination' => [
                'total'         => $pendientes->total(),
                'current_page'  => $pendientes->currentPage(),
                'per_page'      => $pendientes->perPage(),
                'last_page'     => $pendientes->lastPage(),
                'from'          => $pendientes->firstItem(),
                'to'            => $pendientes->lastItem(),
            ],'pendientes'=>$pendientes, 'hoy'=>$hoy];

    }

    public function bonos(Request $request){

        $asesor = $request->b_asesor_id;
        $proyecto = $request->b_proyecto;
        $etapa = $request->b_etapa;
        $desde = $request->desde;
        $hasta = $request->hasta;

        $query = Pago_contrato::join('contratos','pagos_contratos.contrato_id','=','contratos.id')
                ->join('creditos','contratos.id','=','creditos.id')
                ->join('det_comisiones','contratos.id','=','det_comisiones.id')
                ->join('comisiones','det_comisiones.idComision','comisiones.id')
                ->join('vendedores','comisiones.asesor_id', '=','vendedores.id')
                ->join('personal','vendedores.id','=','personal.id')
                ->select('contratos.id as folio','pagos_contratos.pagado','creditos.fraccionamiento','creditos.etapa',
                        'creditos.manzana','creditos.num_lote','det_comisiones.idComision','det_comisiones.total',
                        'det_comisiones.anticipo','det_comisiones.fecha_anticipo','contratos.fecha',
                        'det_comisiones.id as detalleId','det_comisiones.bono','det_comisiones.fecha_bono',
                        DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS asesor")
                );

        if($asesor == ''){
            if($desde == ''){
                if($proyecto == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                elseif($proyecto != '' && $etapa == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                else{
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
            }
            else{
                if($proyecto == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                elseif($proyecto != '' && $etapa == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                else{
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
            }
            
            
        }
        else{
            if($desde == ''){
                if($proyecto == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                elseif($proyecto != '' && $etapa == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                        ->where('comisiones.asesor_id','=',$asesor)
                    ->paginate(12);
                }
                else{
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                        ->where('comisiones.asesor_id','=',$asesor)
                    ->paginate(12);
                }
            }
            else{
                if($proyecto == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('comisiones.asesor_id','=',$asesor)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                    ->paginate(12);
                }
                elseif($proyecto != '' && $etapa == ''){
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                        ->where('comisiones.asesor_id','=',$asesor)
                    ->paginate(12);
                }
                else{
                    $pendientes = $query
                        ->where('contratos.comision','=','1')
                        ->whereBetween('det_comisiones.fecha_anticipo', [$desde, $hasta])
                        ->where('lotes.fraccionamiento_id','=',$proyecto)
                        ->where('lotes.etapa_id','=',$etapa)
                        ->where('contratos.fecha_exp','!=',NULL)
                        ->where('det_comisiones.fecha_bono','!=',NULL)
                        ->where('pagos_contratos.num_pago','=',0)
                        ->where('pagos_contratos.pagado','>',1)
                        ->where('pagos_contratos.tipo_pagare','=',0)
                        ->where('comisiones.asesor_id','=',$asesor)
                    ->paginate(12);
                }
            }
            
        }
        
        return[
            'pagination' => [
                'total'         => $pendientes->total(),
                'current_page'  => $pendientes->currentPage(),
                'per_page'      => $pendientes->perPage(),
                'last_page'     => $pendientes->lastPage(),
                'from'          => $pendientes->firstItem(),
                'to'            => $pendientes->lastItem(),
            ],'anticipos'=>$pendientes];

    }

    public function generarBono(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $detalle_comision = Det_comision::findOrFail($request->id);
        $detalle_comision->fecha_bono = $request->fecha_bono;
        $detalle_comision->save();
    }

    public function noAplicaComision(Request $request){

        $contrato = Contrato::findOrFail($request->id);
        $contrato->comision = 2;
        $contrato->save();
    }


    
}
