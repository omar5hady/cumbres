<?php

namespace App\Http\Controllers\Contrato;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Credito;

class DocumentosController extends Controller
{
    public function printEntregaInterapas(Request $request){
        $contrato = $this->getDatosContrato($request->id);

        $pdf = \PDF::loadview('pdf.Docs.Postventa.entregaInterapas',['contrato' => $contrato]);

        return $pdf->stream('entrega_interapas.pdf');
    }

    private function getDatosContrato($id){
        return Credito::join('personal as p','creditos.prospecto_id','=','p.id')
            ->join('lotes','creditos.lote_id','=','lotes.id')
            ->join('fraccionamientos as proyecto', 'lotes.fraccionamiento_id','=','proyecto.id')
            ->select('p.nombre','p.apellidos','creditos.fraccionamiento as proyecto','creditos.etapa',
                'creditos.manzana','lotes.num_lote','lotes.sublote', 'lotes.calle','lotes.numero',
                'lotes.interior','proyecto.delegacion','proyecto.estado'
            )
            ->where('creditos.id','=',$id)
            ->first();
    }
}
