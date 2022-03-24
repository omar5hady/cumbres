<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto_admin;
use App\Avaluo;
use App\Contrato;
use Carbon\Carbon;
use DB;
use Auth;
use Excel;

//Controlador para modelo Gasto Administrativo.
class GastosAdministrativosController extends Controller
{
    //Funcion que retorna los gastos administrativos por contrato
    public function index(Request $request){
        if(!$request->ajax())return redirect('/');
        
        // Llamada a la función privada que retorna la query principal
        $gastos = $this->getQueryGastos($request);
        $gastos = $gastos->orderBy('contratos.id','asc')->paginate(8);
        
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

    //Funcion que retorna los gastos administrativos por contrato en excel
    public function excel(Request $request){
        // Llamada a la función privada que retorna la query principal
        $gastos = $this->getQueryGastos($request);
        $gastos = $gastos->orderBy('contratos.id','asc')->get();
        //Creación y retorno de los resultados en Excel.
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

    //Funcion privada que retorna la Query principal para los gastos administrativos
    private function getQueryGastos(Request $request){
        $buscar = $request->buscar;
        $buscar2 = $request->buscar2;
        $buscar3 = $request->buscar3;
        $criterio = $request->criterio;
        //Queyr 
        $gastos = Gasto_admin::join('contratos','gastos_admin.contrato_id','=','contratos.id')
            ->join('creditos','contratos.id','=','creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select('contratos.id as folio','personal.nombre', 'personal.apellidos','creditos.fraccionamiento',
                    'creditos.etapa','creditos.manzana','creditos.num_lote','creditos.modelo','gastos_admin.id as gastoId',
        'gastos_admin.concepto','gastos_admin.costo','gastos_admin.observacion','gastos_admin.fecha');
        //Filtro por empresa constructora
        if($request->b_empresa != '')
            $gastos= $gastos->where('lotes.emp_constructora','=',$request->b_empresa);
        //Filtros de Busqueda
        if($buscar != ''){
            switch($criterio){
                case 'gastos_admin.fecha':{
                    $gastos = $gastos->whereBetween($criterio, [$buscar,$buscar2]);
                    break;
                }
                case 'personal.nombre':{
                    $gastos = $gastos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $buscar . '%');
                    break;
                }
                case 'creditos.fraccionamiento':{
                    $gastos = $gastos->where($criterio, '=', $buscar);
                    if($buscar2 != '')//Busqueda por etapa
                        $gastos = $gastos->where('creditos.etapa', '=', $buscar2);
                    if($buscar3 != '')//Busqueda por manzana
                        $gastos = $gastos->where('creditos.manzana', '=', $buscar3);
                    break; 
                }
                default:{
                    $gastos = $gastos->where($criterio, '=', $buscar);
                    break;
                }
            }
        } 

        return $gastos;
    }

    //Función para registrar un nuevo Avaluo
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

    // Función que devuelve el gasto administrativo ligado a un avaluo.
    public function getDatosAvaluo(Request $request){
        if(!$request->ajax())return redirect('/');
        $gasto = Gasto_admin::select('id','fecha')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->where('costo','=',$request->costo)
                ->get();
            
        return ['gasto' => $gasto];
    }

    //Función que retona todos los gastos de avaluo de un contrato
    public function getAvaluos(Request $request){
        if(!$request->ajax())return redirect('/');
        $gasto = Gasto_admin::select('id','fecha','costo')
                ->where('contrato_id','=',$request->folio)
                ->where('concepto','=','Avaluo')
                ->get();
            
        return ['gasto' => $gasto];
    }

    //Función para actualizar un gasto de avaluo.
    public function updateAvaluo(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Acceso al registro del gasto administrativo
            $gasto = Gasto_admin::findOrFail($request->gasto_id);
            $costo_ant = $gasto->costo;
            $contrato_id = $gasto->contrato_id;
            $gasto->costo = $request->costo;
            $gasto->fecha = $request->fecha;
            $gasto->save();
            //Acceso al registro del Avaluo
            $avaluo = Avaluo::findOrFail($request->avaluoId);
            $avaluo->costo = $request->costo;
            $avaluo->save();
            //Acceso al registro del Contrato para actualizar el saldo del contrato
            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    //Función que retorna los datos de los contratos que no han sido cancelados
    public function indexContratos (Request $request){
        if(!$request->ajax())return redirect('/');
        $b = $request->b;
        $b2 = $request->b2;
        $b3 = $request->b3;
        $criterio2 = $request->criterio2;
        //Query principal
        $contratos = Contrato::join('creditos','contratos.id','=','creditos.id')
            ->join('lotes', 'creditos.lote_id', '=', 'lotes.id')
            ->join('clientes','creditos.prospecto_id','=','clientes.id')
            ->join('personal','clientes.id','=','personal.id')
            ->select(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre_cliente"),'contratos.id as folio',
        'creditos.fraccionamiento','creditos.etapa','creditos.manzana','creditos.num_lote')
        ->where('contratos.status','!=',0);
        //Busqueda por empresa
        if($request->b_empresa != '')
            $contratos= $contratos->where('lotes.emp_constructora','=',$request->b_empresa);
        //Filtros de busqueda
        if($b != ''){
            switch($criterio2){
                //Busqueda por nombre de cliente
                case 'personal.nombre': {
                    $contratos = $contratos->where(DB::raw("CONCAT(personal.nombre,' ',personal.apellidos)"), 'like', '%'. $b . '%');
                    break;
                }
                //Busqueda por proyecto
                case 'creditos.fraccionamiento': {
                    $contratos = $contratos->where($criterio2,'=',$b);
                    if($b2 != '')//Etapa
                        $contratos = $contratos->where('creditos.etapa','=',$b2);
                    if($b3 != '')//Manzana
                        $contratos = $contratos->where('creditos.manzana','=',$b3);
                    break;
                }
                default: {
                    $contratos = $contratos->where($criterio2,'=',$b);
                    break;
                }
            }
        }   
        
        $contratos = $contratos->orderBy('contratos.id','asc')
        ->paginate(8);

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

    //Función para registrar un nuevo gasto administrativo
    public function store(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Nuevo registro para tabla Gatos administrativos
            $gastos = new Gasto_admin();
            $gastos->contrato_id = $request->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->costo = $request->costo;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->save();
            //Se accede al registro del contrato para actualizar saldo.
            $contrato = Contrato::findOrFail($request->contrato_id);
            $contrato->saldo = round($contrato->saldo + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    //Función para actualizar un gasto administrativo
    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Se accede al registro del Gatos administrativo
            $gastos = Gasto_admin::findOrFail($request->id);
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->concepto = $request->concepto;
            $gastos->observacion = $request->observacion;
            $gastos->fecha = $request->fecha;
            $gastos->costo = $request->costo;
            $gastos->save();
            //Se accede al registro del contrato para actualizar saldo.
            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant + $request->costo,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    //Función para eliminar el registro de un gasto administrativo
    public function delete(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        try{
            DB::beginTransaction();
            //Se accede al registro del gasto administrativo
            $gastos = Gasto_admin::findOrFail($request->id);
            //En una variable temporal se almacena el costo del gasto
            $costo_ant = $gastos->costo;
            $contrato_id = $gastos->contrato_id;
            $gastos->delete();//Se elimina el registro
            //Se accede al registro del contrato y se actualiza el saldo
            //Restando el costo almacenado en la variable temporal.
            $contrato = Contrato::findOrFail($contrato_id);
            $contrato->saldo = round($contrato->saldo - $costo_ant,2);
            $contrato->save(); 
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }  
    }

    //Función que retorna todos los gastos administrativos de un contrato
    public function getGastos(Request $request){
        if(!$request->ajax())return redirect('/');
        //Gastos
        $gastos=Gasto_admin::select('concepto','costo','id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();
        //Suma total de los gastos
        $totalGastos = Gasto_admin::select(DB::raw("SUM(costo) as sumGasto"))
                    ->groupBy('contrato_id')
                    ->where('contrato_id','=',$request->folio)
                    ->get();
        //Suma total de intereses.
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
