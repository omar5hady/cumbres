<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Credito;

class DocumentosController extends Controller
{
    public function printEntregaInterapas(Request $request){
        setlocale(LC_TIME, 'es_MX.utf8');
        $contrato = $this->getDatosContrato($request->id);

        if($contrato->entrega_real != NULL)
            $contrato->entrega_real = new Carbon($contrato->entrega_real);
        else
            $contrato->entrega_real = new Carbon($contrato->entrega_program);

        $contrato->entrega_real = $contrato->entrega_real->formatLocalized('%d de %B del %Y');

        $pdf = \PDF::loadview('pdf.Docs.Postventa.entregaInterapas',['contrato' => $contrato]);

        return $pdf->stream('entrega_interapas.pdf');
    }

    private function getDatosContrato($id){
        return Credito::join('personal as p','creditos.prospecto_id','=','p.id')
            ->leftJoin('entregas','creditos.id','=','entregas.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('fraccionamientos as proyecto', 'lotes.fraccionamiento_id','=','proyecto.id')
            ->select('p.nombre','p.apellidos','creditos.fraccionamiento as proyecto','creditos.etapa',
                'creditos.manzana','lotes.num_lote','lotes.sublote', 'lotes.calle','lotes.numero',
                'lotes.emp_constructora',
                'lotes.interior','proyecto.delegacion','proyecto.estado as estado_proy',
                'entregas.fecha_program as entrega_program', 'entregas.fecha_entrega_real as entrega_real',
                'proyecto.ciudad as ciudad_proy',
                'proyecto.logo_fracc2'
            )
            ->where('creditos.id','=',$id)
            ->first();
    }
}
