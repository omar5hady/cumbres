<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catalogo_bono;
use App\Bono_recomendado;
use App\Obs_bono;
use Carbon\Carbon;
use App\Contrato;
use App\Cliente;
use DB;
use Auth;
use Excel;

class BonoRecomendadoController extends Controller
{
    public function store($id,$etapa,$cliente,$fecha){
        
        $bono = new Bono_recomendado();
        $bono->id = $id;

        $contrato = Contrato::findOrFail($id);
        $cliente = Cliente::findOrFail($cliente);
        $bono->recomendado = $cliente->nombre_recomendado;

        $datosBono = Catalogo_bono::select('id','fecha_ini','fecha_fin','descripcion','monto','etapa_id')
            ->where('etapa_id','=',$etapa)
            ->where('fecha_fin','>=',$fecha)
            ->where('fecha_ini','<=',$fecha)
            ->orderBy('id','desc')->get();
        if(sizeof($datosBono) > 0){
            $bono->monto = $datosBono[0]->monto;
            $bono->descripcion = $datosBono[0]->descripcion;
            $bono->ini_promo = $datosBono[0]->fecha_ini;
            $bono->fin_promo = $datosBono[0]->fecha_fin;
        }
        $bono->save();
    }

    private function bonoRecIndex($criterio, $buscar, $etapa, $manzana, $lote, $status, $empresa){

        $bonos = Bono_recomendado::join('contratos as con','bonos_recomendados.id','=','con.id')
                    ->join('creditos as cre','con.id','=','cre.id')
                    ->join('lotes as l','cre.lote_id','=','l.id')
                    ->join('fraccionamientos as f','l.fraccionamiento_id','=','f.id')
                    ->join('etapas as e','l.etapa_id','=','e.id')
                    ->join('clientes', 'cre.prospecto_id', '=', 'clientes.id')
                    ->join('vendedores', 'clientes.vendedor_id', '=', 'vendedores.id')
                    ->join('personal as c', 'clientes.id', '=', 'c.id')
                    ->join('personal as v', 'vendedores.id', '=', 'v.id')
                    ->select('con.id','l.num_lote','e.num_etapa','f.nombre as proyecto',
                                DB::raw("CONCAT(c.nombre,' ',c.apellidos) AS nombre_cliente"),
                                DB::raw("CONCAT(v.nombre,' ',v.apellidos) AS nombre_vendedor"),
                                'l.manzana','con.fecha', 'bonos_recomendados.fecha_aut1',
                                'bonos_recomendados.fecha_aut2',
                                'bonos_recomendados.monto',
                                'bonos_recomendados.descripcion',
                                'bonos_recomendados.ini_promo',
                                'bonos_recomendados.fin_promo',
                                'bonos_recomendados.recomendado',
                                'bonos_recomendados.fecha_pago',
                                'bonos_recomendados.status',

                                'bonos_recomendados.proyecto_rec',
                                'bonos_recomendados.etapa_rec',
                                'bonos_recomendados.manzana_rec',
                                'bonos_recomendados.lote_rec',
                                'bonos_recomendados.fecha_compra_rec'
        );      

        
        switch($criterio){
            case 'fraccionamiento':{
                if($buscar != ''){
                    $bonos = $bonos->where('l.fraccionamiento_id','=',$buscar);
                    if($etapa != ''){
                        $bonos = $bonos->where('l.etapa_id','=',$etapa);
                    }
                }
                if($manzana != ''){
                    $bonos = $bonos->where('l.manzana','like', '%'.$manzana.'%');
                }
                if($lote != ''){
                    $bonos = $bonos->where('l.num_lote','=', $lote);
                }
                break;
            }

            case 'cliente':{

                if($buscar != ''){
                    $bonos = $bonos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $buscar. '%');
                }
                break;
            }

            default :{
                if($buscar != ''){
                    $bonos = $bonos->where($criterio, 'like', '%'. $buscar. '%');
                }
                break;
            }
        }

        if($status != ''){
            $bonos = $bonos->where('bonos_recomendados.status','=',$status);
        }

        if($empresa != ''){
            $bonos= $bonos->where('l.emp_constructora','=',$empresa);
        }

        $bonos = $bonos->orderBy('bonos_recomendados.status','asc');

        return $bonos;

    }

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');

        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $empresa = $request->b_empresa;

        

        $bonos = $this->bonoRecIndex($criterio, $buscar, $etapa, $manzana, $lote, $status, $empresa);
        $bonos = $bonos->paginate(8);

        return[
            'pagination' => [
                'total'         => $bonos->total(),
                'current_page'  => $bonos->currentPage(),
                'per_page'      => $bonos->perPage(),
                'last_page'     => $bonos->lastPage(),
                'from'          => $bonos->firstItem(),
                'to'            => $bonos->lastItem(),
            ],
            'bonos'=>$bonos];

    }

    public function excel(Request $request){

        $criterio = $request->criterio;
        $buscar = $request->buscar;
        $etapa = $request->b_etapa;
        $manzana = $request->b_manzana;
        $lote = $request->b_lote;
        $status = $request->status;
        $empresa = $request->b_empresa;

        

        $bonos = $this->bonoRecIndex($criterio, $buscar, $etapa, $manzana, $lote, $status, $empresa);
        $bonos = $bonos->get();

            return Excel::create('Reporte mensual de ingresos', function($excel) use ($bonos){
                $excel->sheet('Cobranza institucional', function($sheet) use ($bonos){
    
                    
                    $sheet->mergeCells('A1:T4');

                    $sheet->mergeCells('B5:H5');
                    $sheet->mergeCells('I5:M5');
                    $sheet->mergeCells('N5:O5');
                    $sheet->mergeCells('Q5:T5');
    
                    
                    $sheet->cell('B5', function($cell) {
    
                        // manipulate the cell
                        $cell->setValue(  'Datos de la venta');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(12);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });

                    $sheet->cell('I5', function($cell) {
    
                        // manipulate the cell
                        $cell->setValue(  'Cliente que nos recomienda');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(12);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });

                    $sheet->cell('N5', function($cell) {
    
                        // manipulate the cell
                        $cell->setValue(  'Periodo');
                        $cell->setFontFamily('Arial Narrow');
                        $cell->setFontSize(12);
                        $cell->setFontWeight('bold');
                        $cell->setAlignment('center');
                    
                    });
    
                  
    
                   
    
    
                        $sheet->cells('A6:T6', function ($cells) {
                            $cells->setBackground('#052154');
                            $cells->setFontColor('#ffffff');
                            // Set font family
                            $cells->setFontFamily('Arial Narrow');
        
                            // Set font size
                            $cells->setFontSize(11);
        
                            // Set font weight to bold
                            $cells->setFontWeight('bold');
                            $cells->setAlignment('center');
                        });
        
    
                        $sheet->setColumnFormat(array(
                            'Q' => '$#,##0.00'
                        ));
    
                        $sheet->row(6,[
                            'Status', 'Folio','Proyecto', 'Etapa', 'Manzana', 'Lote', 
                            'Cliente', 'Fecha venta', 'Nombre', 'Proyecto', 'Etapa', 'Manzana', 'Lote',
                            'Inicio', 'Fin', 'Descripción', 'Monto $', 'Aprobado', 'Autorizado', 'Pago'
                        ]);
        
                        $cont = 6;
                      
                        foreach($bonos as $index => $bono) {
                            $aprob='Sin aprobar';
                            $aut='Sin autorizar';
                            $pago='Sin pago';

                            if($bono->status == 5){
                                $aprob=$aut=$pago=$status = 'Cancelado';
                            }
                                
                            
                            if($bono->status == 3)
                                $status = 'Pagado';
                            
                            if($bono->status == 2)
                                $status = 'Autorizado';

                            if($bono->status == 1)
                                $status = 'Aprobado';

                            if($bono->status == 0)
                                $status = 'Pendiente';

                                $cont++;

                                $ini='';
                                $fin='';
                                
                                if($bono->ini_promo != NULL)
                                    $ini = $bono->ini_promo;
                                if($bono->fin_promo != NULL)
                                    $fin = $bono->ini_promo;

                                if($bono->fecha_aut1 != NULL)
                                    $aprob='Aprobado el: '.$bono->fecha_aut1;

                                if($bono->fecha_aut2 != NULL)
                                    $aut='Autorizado el: '.$bono->fecha_aut1;

                                if($bono->fecha_pago != NULL)
                                    $pago='Pagado el: '.$bono->fecha_aut1;
        
                                $sheet->row($cont, [
                                    $status,
                                    $bono->id, 
                                    $bono->proyecto, 
                                    $bono->num_etapa, 
                                    $bono->manzana, 
                                    $bono->num_lote, 
                                    $bono->nombre_cliente, 
                                    $bono->fecha, 
                                    $bono->recomendado, 
                                    $bono->proyecto_rec, 
                                    $bono->etapa_rec, 
                                    $bono->manzana_rec, 
                                    $bono->lote_rec,
                                    $ini,
                                    $fin,
                                    $bono->descripcion,
                                    $bono->monto,
                                    $aprob,
                                    $aut,
                                    $pago,
                                    
                                ]);	
        
                        }
    
                        $num='A5:T' . $cont;
    
                    
                    
                    $sheet->setBorder($num, 'thin');
                    
                });
                
            }
            
            )->download('xls');

    }

    public function listarObservaciones(Request $request){
        if(!$request->ajax())return redirect('/');
        $observaciones = Obs_bono::select('observacion','usuario','created_at')
        ->where('bono_id','=', $request->id)->orderBy('created_at','desc')->get();

        return ['observacion' => $observaciones];
    }

    public function storeObservacion(Request $request)
    {
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $observacion = new Obs_bono();
        $observacion->bono_id = $request->id;
        $observacion->observacion = $request->observacion;
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function aprobarBono(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $bono = Bono_recomendado::findOrFail($request->id);
        $bono->fecha_aut1 = $fecha;
        $bono->status = 1;
        $bono->save();

        $observacion = new Obs_bono();
        $observacion->bono_id = $request->id;
        $observacion->observacion = 'Bono aprobado.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function cancelarBono(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $bono = Bono_recomendado::findOrFail($request->id);
        $bono->status = 5;
        $bono->save();

        $observacion = new Obs_bono();
        $observacion->bono_id = $request->id;
        $observacion->observacion = 'Bono cancelado.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function autorizarBono(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');
        $fecha = Carbon::now();

        $bono = Bono_recomendado::findOrFail($request->id);
        $bono->fecha_aut2 = $fecha;
        $bono->status = 2;
        $bono->save();

        $observacion = new Obs_bono();
        $observacion->bono_id = $request->id;
        $observacion->observacion = 'Bono autorizado.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function generarPago(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $bono = Bono_recomendado::findOrFail($request->id);
        $bono->fecha_pago = $request->fecha_pago;
        $bono->status = 3;
        $bono->save();

        $observacion = new Obs_bono();
        $observacion->bono_id = $request->id;
        $observacion->observacion = 'Bono pagado.';
        $observacion->usuario = Auth::user()->usuario;
        $observacion->save();
    }

    public function update(Request $request){
        if(!$request->ajax() || Auth::user()->rol_id == 11)return redirect('/');

        $bono = Bono_recomendado::findOrFail($request->id);
        $bono->monto = $request->monto;
        $bono->descripcion = $request->descripcion;
        $bono->recomendado = $request->recomendado;
        $bono->proyecto_rec = $request->proyecto_rec;
        $bono->etapa_rec = $request->etapa_rec;
        $bono->manzana_rec = $request->manzana_rec;
        $bono->lote_rec = $request->lote_rec;
        $bono->save();
    }
}
