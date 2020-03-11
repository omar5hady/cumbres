<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Etapa;
use App\Fraccionamiento;
use App\Credito;
use App\Contrato;
use App\Expediente;
use App\Lote;
use Excel;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function reporteInventario(Request $request){
        $proyectos = Etapa::join('fraccionamientos','etapas.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('etapas.num_etapa','fraccionamientos.nombre as proyecto',
                            'etapas.id','etapas.fraccionamiento_id')->orderBy('fraccionamientos.nombre','asc')
                    ->orderBy('etapas.num_etapa','asc')->get();

        foreach($proyectos as $index => $proyecto){
            $proyecto->totalLotes = Lote::where('fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                                    ->where('etapa_id','=',$proyecto->id)->count();

            $firmadas = Expediente::join('contratos','expedientes.id','=','contratos.id')
                            ->join('creditos','contratos.id','=','creditos.id')
                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')->select('contratos.id')
                            ->where('expedientes.fecha_firma_esc','!=',NULL)
                            ->where('inst_seleccionadas.tipo_credito','!=','Crédito Directo')
                            ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                            ->where('lotes.etapa_id','=',$proyecto->id)->count();

            $proyecto->contado = Contrato::join('creditos','contratos.id','=','creditos.id')
                            ->join('inst_seleccionadas', 'creditos.id', '=', 'inst_seleccionadas.credito_id')
                            ->join('lotes','creditos.lote_id','=','lotes.id')->select('contratos.id')
                            ->where('contratos.status','=',3)
                            ->where('contratos.saldo','<=',0)
                            ->where('inst_seleccionadas.tipo_credito','=','Crédito Directo')
                            ->where('lotes.fraccionamiento_id','=',$proyecto->fraccionamiento_id)
                            ->where('lotes.etapa_id','=',$proyecto->id)->count();
        }


        return ['resumen'=>$proyectos];
    }
    
}
