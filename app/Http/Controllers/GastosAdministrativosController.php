<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto_admin;
use App\Avaluo;
use App\Contrato;
use DB;
use Auth;
use Excel;
use Carbon\Carbon;

class GastosAdministrativosController extends Controller
{
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;

        $query = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
        'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha');

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($buscar==''){
            $gastos = $query
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
        }
        else{
            switch($criterio){
                case 'gastos_admin.fecha':{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->paginate(8);
                    break;
                }
                case 'personal.nombre':{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, 'like', '%'.$buscar . '%')
                        ->orWhere('personal.apellidos','like', '%'.$buscar .'%')
                        ->paginate(8);
                    break;
                }
                case 'creditos.fraccionamiento':{
                    if($buscar2 == '' && $buscar3 == ''){
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->paginate(8);
                        break;
                    }
                    elseif($buscar2 != '' && $buscar3 == ''){
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->paginate(8);
                        break;
                    }
                    elseif($buscar2 == '' && $buscar3 != ''){
                        $gastos =$query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->paginate(8);
                        break;
                    }
                    else{
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->paginate(8);
                        break;
                    }
                }
                default:{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, '=', $buscar)
                        ->paginate(8);
                    break;
                }
            }
        }
        
        
        return [
                'pagination' => [
                    'total'         => $gastos->total(),
                    'current_page'  => $gastos->currentPage(),
                    'per_page'      => $gastos->perPage(),
                    'last_page'     => $gastos->lastPage(),
                    'from'          => $gastos->firstItem(),
                    'to'            => $gastos->lastItem(),
                ],
                'gastos' => $gastos];
    }

    public function excel(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;

        $query = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
        'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha');

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($buscar==''){
            $gastos = $query
                    ->orderBy('contratos.id','asc')
                    ->get();
        }
        else{
            switch($criterio){
                case 'gastos_admin.fecha':{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->whereBetween($criterio, [$buscar,$buscar2])
                        ->get();
                    break;
                }
                case 'personal.nombre':{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, 'like', '%'.$buscar . '%')
                        ->orWhere('personal.apellidos','like', '%'.$buscar .'%')
                        ->get();
                    break;
                }
                case 'creditos.fraccionamiento':{
                    if($buscar2 == '' && $buscar3 == ''){
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->get();
                        break;
                    }
                    elseif($buscar2 != '' && $buscar3 == ''){
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->get();
                        break;
                    }
                    elseif($buscar2 == '' && $buscar3 != ''){
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->get();
                        break;
                    }
                    else{
                        $gastos = $query
                            ->orderBy('contratos.id','asc')
                            ->where($criterio, '=', $buscar)
                            ->where('creditos.etapa', '=', $buscar2)
                            ->where('creditos.manzana', '=', $buscar3)
                            ->get();
                        break;
                    }
                }
                default:{
                    $gastos = $query
                        ->orderBy('contratos.id','asc')
                        ->where($criterio, '=', $buscar)
                        ->get();
                    break;
                }
            }
        }
        
        return Excel::create('Gastos Administrativos', function($excel) use ($gastos){
                $excel->sheet('gastos', function($sheet) use ($gastos){
                    
                    $sheet->row(1, [
                        '# Ref', 'Cliente', 'Proyecto', 'Etapa', 'Manzana',
                        '# Lote','Tipo de gasto', 'Fecha', 'Monto', 'Observacion'
                    ]);

                    $sheet->setColumnFormat(array(
                        'I' => '$#,##0.00',
                    ));


                    $sheet->cells('A1:J1', function ($cells) {
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

                    foreach($gastos as $index => $gasto) {
                        $cont++;

                        setlocale(LC_TIME, 'es_MX.utf8');
                        $fecha1 = new Carbon($gasto->fecha);
                        $gasto->fecha = $fecha1->formatLocalized('%d de %B de %Y');

                        $sheet->row($index+2, [
                            $gasto->folio, 
                            $gasto->nombre. ' ' . $gasto->apellidos,
                            $gasto->fraccionamiento,
                            $gasto->etapa,
                            $gasto->manzana,
                            $gasto->num_lote,
                            $gasto->concepto,
                            $gasto->fecha,
                            $gasto->costo,
                            $gasto->observacion,

                        ]);	
                    }
                    $num='A1:J' . $cont;
                    $sheet->setBorder($num, 'thin');
                });
            }
        )->download('xls');
    }

    public function storeAvaluo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $gasto = new Gasto_admin();
            $gasto->contrato_id = $request->id;
            $gasto->concepto = 'Avaluo';
            $gasto->costo = $request->costo;
            $gasto->fecha = $request->fecha;
            $gasto->observacion = '';
            $gasto->save();

            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->save();

            $contrato = Contrato::findOrFail($request->id);
            $contrato->saldo = round($contrato->saldo + $request->costo, 2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function getDatosAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        $gasto = Gasto_admin::select('id','fecha')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->where('costo','=',$request->costo)
                ->get();
            
        return ['gasto' => $gasto];
    }

    public function getAvaluos(Request $request){
        if(!$request->ajax())return redirect('/');
        $gasto = Gasto_admin::select('id','fecha','costo')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->get();
            
        return ['gasto' => $gasto];
    }

    public function updateAvaluo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $gasto = Gasto_admin::findOrFail($request->gasto_id);
            $costo_ant = $gasto->costo;
            $contrato_id = $gasto->contrato_id;
            $gasto->costo = $request->costo;
            $gasto->fecha = $request->fecha;
            $gasto->save();

            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->save();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function indexContratos (Request $request){
        if(!$request->ajax())return redirect('/');
        $b = $request->b;
        $b2 = $request->b2;
        $b3 = $request->b3;
        $criterio2 = $request->criterio2;

        $query = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote');

        if($request->b_empresa != ''){
            $query= $query->where('lotes.emp_constructora','=',$request->b_empresa);
        }

        if($b == ''){
            $contratos = $query
           ->where('contratos.status','!=',0)
           ->orderBy('contratos.id','asc')
           ->paginate(8);
        }else{
            switch($criterio2){
                case 'contratos.id': {
                    $contratos = $query
                    ->where('contratos.status','!=',0)
                    ->where('contratos.id','=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                }
                case 'personal.nombre': {
                    $contratos = $query
                    ->where('contratos.status','!=',0)
                    ->where('personal.nombre','LIKE','%'.$b.'%')
                    ->orwhere('contratos.status','!=',0)
                    ->where('personal.apellidos','LIKE','%'.$b.'%')
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                }

                case 'creditos.fraccionamiento': {
                    if($b2 == '' && $b3 == ''){
                    $contratos = $query
                    ->where('contratos.status','!=',0)
                    ->where($criterio2,'=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
                    }elseif($b2 != '' && $b3 == ''){
                        $contratos = $query
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.etapa','=',$b2)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }elseif ($b2 == '' && $b3 != '') {
                        $contratos = $query
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.manzana','=',$b3)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }else {
                        $contratos = $query
                        ->where('contratos.status','!=',0)
                        ->where($criterio2,'=',$b)
                        ->where('creditos.etapa','=',$b2)
                        ->where('creditos.manzana','=',$b3)
                        ->orderBy('contratos.id','asc')
                        ->paginate(8);
                        break;
                    }
                }
                default: {
                    $contratos = $query
                    ->where('contratos.status','!=',0)
                    ->where($criterio2,'=',$b)
                    ->orderBy('contratos.id','asc')
                    ->paginate(8);
                    break;
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
            ],
            'contratos' => $contratos];
        

    }

    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->costo = $request->costo;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->save();

            $contrato = Contrato::findOrFail($request->contrato_id);
            $contrato->saldo = round($contrato->saldo + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = Gasto_admin::findOrFail($request->id);
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->costo = $request->costo;
            $gastos->save();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            $gastos = Gasto_admin::findOrFail($request->id);
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->delete();

            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    public function getGastos(Request $request){
        if(!$request->ajax())return redirect('/');
        $gastos=Gasto_admin::select('concepto','costo','id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();

        $totalGastos = Gasto_admin::select(DB::raw("SUM(costo) as sumGasto"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();

        $totalIntereses = Gasto_admin::select(DB::raw("SUM(costo) as sumIntereses"))
                        ->groupBy('contrato_id')
                        ->where('contrato_id','=',$request->folio)
                        ->where('concepto','=','Interes Ordinario')
                        ->get();

        return ['gastos' => $gastos,
                'totalGastos' => $totalGastos,
                'totalIntereses' => $totalIntereses];
    }
}
