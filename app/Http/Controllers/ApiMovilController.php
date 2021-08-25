<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cliente;
use App\Contrato;

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
                                'personal.celular', 'personal.clv_lada',
                                'personal.email')
                        ->where('entregas.status','=',1)
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
                                                'lotes.manzana','lotes.num_lote as lote'
                                        )
                                        ->where('creditos.prospecto_id','=',$cliente->id)
                                        ->where('entregas.status','=',1)
                                        ->get();
        }
        
        
    
        return ['clientes'=>$clientes];
    }
    
}
