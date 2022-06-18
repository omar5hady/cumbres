<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Cliente;
use App\Contrato;
use DB;

/* Controlador utilizado para la obtencion de datos sobre los clientes que han adquirido una casa, 
    esta información se usa para la app movil.
*/

class ApiMovilController extends Controller
{
    public function index(){
        $clientes = Cliente::join('personal','clientes.id','=','personal.id')
                        ->join('creditos','clientes.id','=','creditos.prospecto_id')
                        ->join('contratos','creditos.id','=','contratos.id')
                        ->join('lotes','creditos.lote_id','=','lotes.id')
                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                        ->join('modelos','lotes.modelo_id','=','modelos.id')
                        ->join('entregas','contratos.id','=','entregas.id')
                        ->select('personal.id', 'personal.nombre', 'personal.apellidos',
                                DB::raw("CONCAT(personal.nombre,' ',personal.apellidos) AS nombre"),
                                DB::raw("CONCAT(personal.clv_lada,'',personal.celular) AS celular"),
                                // 'personal.celular', 'personal.clv_lada',
                                'personal.email')
                        ->where('entregas.status','=',1)
                        ->where('clientes.app_alta','=',0)
                        ->where('etapas.num_etapa','!=','EXTERIOR')
                        ->where('modelos.nombre','!=','Terreno')
                        ->groupBy('personal.id');
        
        $clientes = $clientes->get();

        foreach ($clientes as $key => $cliente) {
            $cliente->propiedades = Contrato::join('creditos','contratos.id','=','creditos.id')
                                        ->join('entregas','contratos.id','=','entregas.id')
                                        ->join('lotes','creditos.lote_id','=','lotes.id')
                                        ->join('etapas','lotes.etapa_id','=','etapas.id')
                                        ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                                        ->select('fraccionamientos.nombre as fraccionamiento','etapas.num_etapa as privada',
                                                'lotes.manzana','lotes.num_lote as lote', 'lotes.fraccionamiento_id',
                                                'lotes.etapa_id',
                                                'lotes.calle','lotes.numero','lotes.interior'
                                        )
                                        ->where('creditos.prospecto_id','=',$cliente->id)
                                        ->where('entregas.status','=',1)
                                        ->get();
        }
        
        
    
        return ['clientes'=>$clientes];
    }
    
}
