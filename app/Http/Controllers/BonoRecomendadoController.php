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

    public function index(Request $request){
        if(!$request->ajax())return redirect('/');

        $criterio = $request->criterio;

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
                if($request->buscar != ''){
                    $bonos = $bonos->where('l.fraccionamiento_id','=',$request->buscar);
                    if($request->b_etapa != ''){
                        $bonos = $bonos->where('l.etapa_id','=',$request->b_etapa);
                    }
                }
                if($request->b_manzana != ''){
                    $bonos = $bonos->where('l.manzana','like', '%'.$request->b_manzana.'%');
                }
                if($request->b_lote != ''){
                    $bonos = $bonos->where('l.num_lote','=', $request->b_lote);
                }
                break;
            }

            case 'cliente':{

                if($request->buscar != ''){
                    $bonos = $bonos->where(DB::raw("CONCAT(c.nombre,' ',c.apellidos)"), 'like', '%'. $request->buscar. '%');
                }
                break;
            }

            default :{
                if($request->buscar != ''){
                    $bonos = $bonos->where($criterio, 'like', '%'. $request->buscar. '%');
                }
                break;
            }
        }

        if($request->status != ''){
            $bonos = $bonos->where('bonos_recomendados.status','=',$request->status);
        }

        if($request->b_empresa != ''){
            $bonos= $bonos->where('l.emp_constructora','=',$request->b_empresa);
        }

        $bonos = $bonos->orderBy('bonos_recomendados.status','asc')
                        ->paginate(8);

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
        if(!$request->ajax())return redirect('/');
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
        if(!$request->ajax())return redirect('/');

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
        if(!$request->ajax())return redirect('/');
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
        if(!$request->ajax())return redirect('/');

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
        if(!$request->ajax())return redirect('/');

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
